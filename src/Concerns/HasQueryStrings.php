<?php

/** @noinspection PhpUnused */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\WiredTable;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * @mixin WiredTable
 */
trait HasQueryStrings
{
    private function queryStringKey(string $key): string
    {
        return Str::of($key)
            ->when($this->slug, fn (Stringable $str, $slug) => $str->prepend($slug, '.'));
    }

    public function queryString(): array
    {
        return [
            "search" => ['except' => '', 'as' => $this->queryStringKey('search')],
            "sorting" => ['except' => [], 'as' => $this->queryStringKey('sort')],
            "filterValues" => ['except' => '', 'as' => $this->queryStringKey('filters')],
        ];
    }
}
