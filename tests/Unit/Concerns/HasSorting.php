<?php

/** @noinspection SqlRedundantOrderingDirection */

/** @noinspection SqlResolve */

use DefStudio\WiredTables\Enums\Sorting;
use DefStudio\WiredTables\Exceptions\SortingException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('tells if multiple sorting is enabled', function () {
    $table = fakeTable();

    expect($table->supportMultipleSorting())->toBeFalse();

    $table->configuration()->multipleSorting();

    expect($table->supportMultipleSorting())->toBeTrue();
});

it("doesn't apply sorting to non sortable columns", function () {
    $table = fakeTable();

    expect(fn () => $table->sort('Not Sortable'))
        ->toThrow(SortingException::class);
});

it('can sort a column', function () {
    $table = fakeTable();

    $table->sort('Name');
    expect($table)->rawQuery()->toContain('order by "name" asc');

    $table->sort('Name');
    expect($table)->rawQuery()->toContain('order by "name" desc');

    $table->sort('Name');
    expect($table)->rawQuery()->not->toContain('order by "name"');
});

it('can sort two columns', function () {
    $table = fakeTable();
    $table->configuration()->multipleSorting();

    $table->sort('Name');
    expect($table->sorting)->toBe([
        'Name' => 'asc',
    ]);


    $table->sort('Owner');
    expect($table->sorting)->toBe([
        'Name' => 'asc',
        'Owner' => 'asc',
    ]);

    $table->sort('Name');
    expect($table->sorting)->toBe([
        'Name' => 'desc',
        'Owner' => 'asc',
    ]);

    $table->sort('Name');
    expect($table->sorting)->toBe([
        'Owner' => 'asc',
    ]);

    $table->sort('Name');
    expect($table->sorting)->toBe([
        'Owner' => 'asc',
        'Name' => 'asc',
    ]);
});

it('returns a column sort direction', function () {
    $table = fakeTable();
    $table->configuration()->multipleSorting();

    $table->sort('Name');
    expect($table->getSortDirection('Name'))->toBe(Sorting::asc);

    $table->sort('Name');
    expect($table->getSortDirection('Name'))->toBe(Sorting::desc);

    $table->sort('Name');
    expect($table->getSortDirection('Name'))->toBe(Sorting::none);
});

it('returns a column sort position', function () {
    $table = fakeTable();
    $table->configuration()->multipleSorting();

    $table->sort('Owner');
    $table->sort('Name');

    expect($table->getSortPosition('Name'))->toBe(2);
    expect($table->getSortPosition('Owner'))->toBe(1);
});

it('applies closure sorting', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name')
                ->sortable(function (Builder $query, $dir) {
                    $query->orderBy('foo', $dir->value);
                });
        }
    });

    $table->sort('Owner');
    expect($table)->rawQuery()->toBe('select * from "cars" order by "foo" asc limit 10 offset 0');
});

it('sorts first level relationships', function () {
    $table = fakeTable();

    $table->sort('Owner');
    expect($table)->rawQuery()->toBe('select * from "cars" order by (select "name" from "users" where "cars"."user_id" = "users"."id") asc limit 10 offset 0');
});

it('sorts by field', function () {
    $table = fakeTable();

    $table->sort('Name');
    expect($table)->rawQuery()->toBe('select * from "cars" order by "name" asc limit 10 offset 0');
});
