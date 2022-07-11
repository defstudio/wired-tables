<?php

/** @noinspection MultipleExpectChainableInspection */

/** @noinspection SqlDialectInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */

/** @noinspection PhpMultipleClassDeclarationsInspection */

use DefStudio\WiredTables\Elements\Filter;
use DefStudio\WiredTables\Exceptions\FilterException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\actingAs;

test('filters are booted', function () {
    $table = fakeTable();

    expect($table->filters)
        ->toBeArray()
        ->not->toBeEmpty();
});

test('filters are mounted', function () {
    $table = fakeTable();

    expect($table->filterValues)
        ->toBeArray()
        ->toHaveKey('brand');
});

test('cached filters are mounted', function () {
    actingAs(new User(['id' => 42]));
    Cache::put("httplocalhost-42-state-filters", [
        'brand' => 'foo',
    ]);
    $table = fakeTable();

    expect($table->filterValues)->toBe([
        'brand' => 'foo',
    ]);
});

test('cached filters can be overridden by a query string', function () {
    actingAs(new User(['id' => 42]));
    Cache::put("httplocalhost-42-state-filters", [
        'brand' => 'foo',
    ]);
    $table = fakeTable();
    $table->filterValues = [
        'brand' => 'bar',
    ];

    $table->bootedHasFilters();

    expect($table->filterValues)->toBe([
        'brand' => 'bar',
    ]);

    expect(Cache::get('httplocalhost-42-state-filters'))->toBe([
        'brand' => 'bar',
    ]);
});

test('cached filters are cleared with filter values', function () {
    actingAs(new User(['id' => 42]));
    Cache::put("httplocalhost-42-state-filters", [
        'brand' => 'foo',
    ]);
    $table = fakeTable();

    $table->clearFilter('brand');

    expect(Cache::get('httplocalhost-42-state-filters'))->toBe([
        'brand' => null,
    ]);
});

test('cached filters are updated when filter values change', function () {
    actingAs(new User(['id' => 42]));
    $table = fakeTable();
    $table->filterValues = [
        'brand' => 'bar',
    ];

    $table->updatedFilterValues();

    expect(Cache::get('httplocalhost-42-state-filters'))->toBe([
        'brand' => 'bar',
    ]);
});

test('existing filters are not cleared on mount', function () {
    $table = fakeTable();

    $table->filterValues['brand'] = 'foo';

    $table->bootedHasFilters();

    expect($table->filterValues)->toMatchArray([
        'brand' => 'foo',
    ]);
});

test('filters must be defined only inside [->filters()] method', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column("name");
        }

        public function addFilter(): void
        {
            $this->filter('test');
        }
    });

    expect(fn () => $table->addFilter())
        ->toThrow(FilterException::class);
});

test('filters must be unique', function () {
    fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column("name");
        }

        protected function filters(): void
        {
            $this->filter("test");
            $this->filter("test");
        }
    });
})->throws(FilterException::class);

it('can retrieve a filter', function () {
    $table = fakeTable();

    expect($table->getFilter('brand'))
        ->toBeInstanceOf(Filter::class)
        ->name()->toBe('Brand');
});

it('can retrieve a filter by name', function () {
    $table = fakeTable();

    expect($table->getFilterByName('Brand'))
        ->toBeInstanceOf(Filter::class)
        ->name()->toBe('Brand');
});

it('cleans up checkbox filters when unckeched', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
        }

        protected function filters(): void
        {
            $this->filter('check')->checkbox();
        }
    });

    $table->filterValues['check'] = 0;

    $table->updatedFilterValues();

    expect($table->filterValues['check'])->toBeNull();
});

it('can tell if it has filters', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
        }
    });

    expect($table->hasFilters())->toBeFalse();

    expect(fakeTable()->hasFilters())->toBeTrue();
});

it('can tell if filters selector should be shown', function (WiredTable $table, bool $visible) {
    expect($table->shouldShowFiltersSelector())->toBe($visible);
})->with([
    'no filters' => [
        'table' => fn () => fakeTable(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('Name');
            }
        }),
        'visible' => false,
    ],
    'no global filters' => [
        'table' => fn () => fakeTable(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('Name');
            }

            protected function filters(): void
            {
                $this->filter('Name')->displayOnColumn();
            }
        }),
        'visible' => false,
    ],
    'hidden filter' => [
        'table' => fn () => fakeTable(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('Name');
            }

            protected function filters(): void
            {
                $this->filter('Name')->hidden();
            }
        }),
        'visible' => false,
    ],
    'visible filter' => [
        'table' => fn () => fakeTable(),
        'visible' => true,
    ],
]);

it('can tell if column filters should be shown', function (WiredTable $table, bool $visible) {
    expect($table->shouldShowColumnFilters())->toBe($visible);
})->with([
    'no filters' => [
        'table' => fn () => fakeTable(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('Name');
            }
        }),
        'visible' => false,
    ],
    'no column filters' => [
        'table' => fn () => fakeTable(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('Name');
            }

            protected function filters(): void
            {
                $this->filter('Name');
            }
        }),
        'visible' => false,
    ],
    'hidden filter' => [
        'table' => fn () => fakeTable(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('Name');
            }

            protected function filters(): void
            {
                $this->filter('Name')->displayOnColumn()->hidden();
            }
        }),
        'visible' => false,
    ],
    'visible filter' => [
        'table' => fn () => fakeTable(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('Name');
            }

            protected function filters(): void
            {
                $this->filter('Name')->displayOnColumn();
            }
        }),
        'visible' => true,
    ],
]);

it('can extract active filters', function () {
    $table = fakeTable();

    expect($table->activeFilters())->toBeEmpty();

    $table->filterValues['brand'] = 'fiat';

    expect($table->activeFilters())
        ->toBeCollection()
        ->not->toBeEmpty()
        ->first()->name()->toBe('Brand');
});

it('can extract global and column filters', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
        }

        protected function filters(): void
        {
            $this->filter('Name')->displayOnColumn();
            $this->filter('Brand');
        }
    });

    expect($table->globalFilters())
        ->toBeCollection()
        ->toHaveCount(1)
        ->first()->name()->toBe('Brand');

    expect($table->columnFilters())
        ->toBeCollection()
        ->toHaveCount(1)
        ->first()->name()->toBe('Name');
});

it('can clear filters', function () {
    $table = fakeTable();
    $table->filterValues['brand'] = 'lamborghini';

    $table->clearFilter('brand');

    expect($table->filterValues['brand'])->toBeNull();
});

it('can apply filters', function () {
    enableDebug();
    $table = fakeTable();

    $table->filterValues['brand'] = 'alfa';

    $query = Car::query();
    $table->applyFilters($query);

    expect($table->debugQuery($query))
        ->toBe('select * from "cars" where "brand" = \'alfa\'');
});
