<?php


it('can dump values', function () {
    $table = fakeTable();

    $table->dump("a", 1, ['666']);
    $table->dump(42);

    expect($table->dumps())->sequence(
        fn ($dump) => $dump->values()->toBe(['a', 1, ['666']]),
        fn ($dump) => $dump->values()->toBe([42]),
    );
});

it('can assign a label to a dump', function () {
    $table = fakeTable();

    $table->dump("a", 1, ['666'])->label('foo');
    $table->dump(42)->label('bar');

    expect($table->dumpLabels())->sequence(
        fn ($label) => $label->toBe('foo'),
        fn ($label) => $label->toBe('bar'),
    );
});
