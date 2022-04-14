<?php



it('can return text classes', function () {
    $table = fakeTable();

    expect($table->configuration()->getTextClasses())
        ->toBe('text-left text-gray-800 text-sm');
});
