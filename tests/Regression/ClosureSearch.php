<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('can search in two columns with a closure search', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name')->searchable(fn (Builder $query) => $query);
            $this->column('Color')->searchable();
            $this->column('Owner', 'owner.name');
        }
    });

    $table->search = 'foo';

    expect($table)->rawQuery()->toBe('select * from "cars" where ("color" like \'%foo%\') limit 10 offset 0');
})->only();
