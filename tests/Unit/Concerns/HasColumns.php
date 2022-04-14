<?php

use DefStudio\WiredTables\Exceptions\ColumnException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

test('columns are booted', function () {
    $table = fakeTable();

    expect($table->columns)
        ->toBeArray()
        ->not->toBeEmpty();
});

test('columns must be defined', function () {
    $table = new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
        }
    };

    expect(fn () => $table->bootHasColumns())
        ->toThrow(ColumnException::class);
});

test('columns can be defined only inside [->columns()] method', function () {
    $table = new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        public function addColumn()
        {
            $this->column('test');
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name');
        }
    };

    expect(fn () => $table->addColumn())
        ->toThrow(ColumnException::class);
});
