<?php


use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('can return text classes', function(){
    $table = fakeTable();

    expect($table->configuration()->getTextClasses())
        ->toBe('text-left text-gray-800 text-sm');
});
