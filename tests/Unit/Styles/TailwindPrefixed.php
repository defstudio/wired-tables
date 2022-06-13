<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PHPUnit\Framework\ExpectationFailedException;

test('all tailwind classes are prefixed', function () {
    $patterns = [
        "/[^:]class=['\"]([^'^\"]*)['\"]/m",
        "/:class=['\"]{['\"]([^'^\"]*)['\"]:/m",
    ];

    foreach ($patterns as $pattern) {
        foreach (listFolderFiles(__DIR__ . "/../../../resources/views/tailwind_3") as $file) {
            $prefixed_file = Str::of($file)->replace('resources/views/tailwind_3', 'resources/views/tailwind_3_prefixed')->toString();

            expect($prefixed_file)->toBeFile();

            $file_match = [];
            preg_match_all($pattern, File::get($file), $file_match);

            $prefixed_match = [];
            preg_match_all($pattern, File::get($prefixed_file), $prefixed_match);


            if (isset($file_match[1])) {
                foreach ($file_match[1] as $class_index => $class) {
                    $items = Str::of($class)->replace("\n", '')->replace("\t", '')->explode(' ')->filter()->values();

                    $prefixed_items = Str::of($prefixed_match[1][$class_index])->replace("\n", '')->replace("\t", '')->explode(' ')->filter()->values();

                    $items->each(function (string $item, $item_index) use ($file, $prefixed_items) {
                        $prefixed_item = $prefixed_items->get($item_index);

                        if (Str::of($item)->contains("{{")) {
                            return;
                        }

                        if (Str::of($item)->before('[')->contains(':')) {
                            $item = Str::of($item)->after(':')->toString();
                            $prefixed_item = Str::of($prefixed_item)->after(':')->toString();
                        }

                        try {
                            if (Str::of($item)->startsWith('-')) {
                                expect($prefixed_item)->toBe("-tw$item");

                                return;
                            }

                            expect($prefixed_item)->toBe("tw-$item");
                        } catch (ExpectationFailedException $e) {
                            throw new ExpectationFailedException("Class was not prefixed in [$file]", $e->getComparisonFailure());
                        }
                    });
                }
            }
        }
    }
});
