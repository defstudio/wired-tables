<?php

/** @noinspection ALL */

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\WiredTables\WiredTable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('query is booted', function () {
    $table = new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('name');
        }
    };

    $table->bootHasConfiguration();
    $table->bootHasColumns();
    $table->mountHasPagination();

    expect(fn () => $table->rows)->toThrow(Error::class);

    $table->bootBuildsQuery();

    expect(fn () => $table->rows)->not->toThrow(Error::class);
});

it('applies eager loading', function () {
    $table = fakeTable();

    expect($table->rows()->getEagerLoads())->toHaveKeys([
        'owner',
    ]);
});

it('applies sorting', function () {
    $table = fakeTable();

    $table->sort('Name');

    expect($table)->rawQuery()->toContain('order by "name" asc');
});

it('applies search', function () {
    $table = fakeTable();

    $table->search = 'fooo';

    expect($table)->rawQuery()->toContain('where ("name" like \'%fooo%\' or exists (select * from "users" where "cars"."user_id" = "users"."id" and "name" like \'%fooo%\'))');
});

it('applies filters', function () {
    $table = fakeTable();

    $table->filterValues['brand'] = 'ferrari';

    expect($table)->rawQuery()->toContain('"brand" = \'ferrari\'');
});

it('applies pagination', function () {
    Car::factory(11)->create();

    $table = fakeTable();

    expect($table->rows)
        ->toBeInstanceOf(LengthAwarePaginator::class)
        ->perPage()->toBe(10)
        ->lastPage()->toBe(2);
});

it("doesn't apply pagination if page size is 'all'", function () {
    Car::factory(11)->create();

    $table = fakeTable();

    $table->setPageSize('all');

    expect($table->rows)
        ->toBeInstanceOf(Collection::class)
        ->count()->toBe(11);
});

it("doesn't apply pagination if disabled", function () {
    Car::factory(11)->create();

    $table = fakeTable();
    $table->configuration()->disablePagination();

    expect($table->rows)
        ->toBeInstanceOf(Collection::class)
        ->count()->toBe(11);
});

it("returns rows property", function () {
    Car::factory(11)->create();

    $table = fakeTable();
    $table->configuration()->disablePagination();

    expect($table->rows)
        ->toBeInstanceOf(Collection::class)
        ->count()->toBe(11);
});

it('returns a debuggable query', function () {
    Car::factory(11)->create();

    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query()->where('name', 'foo');
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name');
        }
    });

    enableDebug();

    /** @noinspection SqlResolve */
    expect($table->debugQuery())->toBe('select * from "cars" where "name" = \'foo\' limit 10 offset 0');
});

it("doesn't return a debuggable query if debug is disabled", function () {
    Car::factory(11)->create();

    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query()->where('name', 'foo');
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name');
        }
    });

    Config::set('app.debug', false);

    /** @noinspection SqlResolve */
    expect($table->debugQuery())->toBe('');
});

it("can get selected rows query", function () {
    $table = fakeTable();
    enableDebug();

    $table->selectRows([1, 42, 666]);

    expect($table->debugQuery($table->selectedRows()))->toContain('where "id" in (1, 42, 666)');
});

it("can get selectedRows property", function () {
    enableDebug();
    Car::factory(10)->create();

    $table = fakeTable();

    $table->selectRows([1, 3, 6]);

    expect($table->selectedRows)
        ->toBeInstanceOf(Collection::class)
        ->pluck('id')
        ->toArray()
        ->toBe([1, 3, 6]);
});
