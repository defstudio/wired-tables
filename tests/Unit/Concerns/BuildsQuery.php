<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

test('query is booted', function () {
    $table = new class extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('name');
        }
    };

    $table->bootHasColumns();

    expect(fn () => $table->rowsQuery())->toThrow(Error::class);

    $table->bootBuildsQuery();

    expect(fn () => $table->rowsQuery())->not->toThrow(Error::class);
});
