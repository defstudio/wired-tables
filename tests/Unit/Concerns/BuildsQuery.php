<?php /** @noinspection ALL */

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\WiredTables\Configurations\TableConfiguration;
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

    $table->bootHasColumns();

    expect(fn () => $table->rowsQuery())->toThrow(Error::class);

    $table->bootBuildsQuery();

    expect(fn () => $table->rowsQuery())->not->toThrow(Error::class);
});

it('applies eager loading', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name');
        }
    });


    expect($table->rowsQuery()->getEagerLoads())->toHaveKeys([
        'owner',
    ]);
});

it('applies sorting', function(){
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name')->sortable();
            $this->column('Owner', 'owner.name');
        }
    });

    $table->sort('Name');

    expect($table)->rawQuery()->toContain('order by "name" asc');
});

it('applies pagination', function(){
    Car::factory(11)->create();

    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name');
        }
    });

    expect($table->paginatedResults())
        ->toBeInstanceOf(LengthAwarePaginator::class)
        ->perPage()->toBe(10)
        ->lastPage()->toBe(2);
});

it("doesn't apply pagination if page size is 'all'", function(){
    Car::factory(11)->create();

    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name');
        }
    });

    $table->setPageSize('all');

    expect($table->paginatedResults())
        ->toBeInstanceOf(Collection::class)
        ->count()->toBe(11);
});

it("doesn't apply pagination if disabled", function(){
    Car::factory(11)->create();

    $table = fakeTable(new class () extends WiredTable {
        protected function configure(TableConfiguration $configuration): void
        {
            $configuration->disablePagination();
        }

        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name');
        }
    });

    expect($table->paginatedResults())
        ->toBeInstanceOf(Collection::class)
        ->count()->toBe(11);
});

it("returns rows property", function(){
    Car::factory(11)->create();

    $table = fakeTable(new class () extends WiredTable {
        protected function configure(TableConfiguration $configuration): void
        {
            $configuration->disablePagination();
        }

        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name');
        }
    });

    expect($table->rows)
        ->toBeInstanceOf(Collection::class)
        ->count()->toBe(11);
});

it('returns a debuggable query', function(){
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

    Config::set('app.debug', true);

    /** @noinspection SqlResolve */
    expect($table->debugQuery())->toBe('select * from "cars" where "name" = \'foo\' limit 10 offset 0');
});

it("doesn't return a debuggable query if debug is disabled", function(){
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
