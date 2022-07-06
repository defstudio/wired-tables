<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('can search in two columns while get json values', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Details', 'data->foo')->searchable();
        }
    });

    $table->search = 'bar';

    expect($table)->rawQuery()->toBe('select * from "cars" where (json_extract("data", \'$."foo"\') like \'%bar%\') limit 10 offset 0');
});
