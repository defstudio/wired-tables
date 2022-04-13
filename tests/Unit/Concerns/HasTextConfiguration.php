<?php


use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('can return text classes', function(){
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name')->sortable();
            $this->column('Owner', 'owner.name')->sortable();
        }
    });

    expect($table->configuration()->getTextClasses())
        ->toBe('text-left text-gray-800 text-sm');
});
