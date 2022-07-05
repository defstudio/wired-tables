<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('can search in two columns while get json values', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation {
            return Car::query();
        }

        protected function columns(): void {
            $this->column('Name')->searchable();
            $this->column('Details', 'data->foo');
        }
    });

    $table->search = 'foo';

    expect($table)->rawQuery()->toBe('select * from "cars" where ("foo" like \'%foo%\') limit 10 offset 0');
})->only();
