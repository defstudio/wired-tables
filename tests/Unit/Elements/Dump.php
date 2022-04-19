<?php

use DefStudio\WiredTables\Elements\Dump;

test('default label is "Misc"', function () {
    $dump = new Dump();

    expect($dump->getLabel())->toBe("Misc");
});

it('can set a custom label', function () {
    $dump = new Dump();
    $dump->label('foo');

    expect($dump->getLabel())->toBe('foo');
});

it('can return its values', function () {
    $dump = new Dump(1, 2, "foo");

    expect($dump->values())->toBe([1, 2, "foo"]);
});
