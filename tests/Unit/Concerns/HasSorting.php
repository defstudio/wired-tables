<?php /** @noinspection SqlRedundantOrderingDirection */

/** @noinspection SqlResolve */

use DefStudio\WiredTables\Configurations\TableConfiguration;
use DefStudio\WiredTables\Enums\Sorting;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('tells if pagination is enabled', function(){
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('name');
        }
    });

    expect($table->supportMultipleSorting())->toBeFalse();

    $table->configuration()->multipleSorting();

    expect($table->paginationEnabled())->toBeTrue();
});

it('can sort a column', function(){
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

    $table->sort('name');
    expect($table)->rawQuery()->toContain('order by "name" asc');

    $table->sort('name');
    expect($table)->rawQuery()->toContain('order by "name" desc');

    $table->sort('name');
    expect($table)->rawQuery()->not->toContain('order by "name"');
});


it('can sort two columns', function(){
    $table = fakeTable(new class () extends WiredTable {
        protected function configure(TableConfiguration $configuration): void
        {
            $configuration->multipleSorting();
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

    $table->sort('name');
    expect($table->sorting)->toBe([
        'name' => 'asc',
    ]);


    $table->sort('owner.name');
    expect($table->sorting)->toBe([
        'name' => 'asc',
        'owner.name' => 'asc',
    ]);

    $table->sort('name');
    expect($table->sorting)->toBe([
        'name' => 'desc',
        'owner.name' => 'asc',
    ]);

    $table->sort('name');
    expect($table->sorting)->toBe([
        'owner.name' => 'asc',
    ]);

    $table->sort('name');
    expect($table->sorting)->toBe([
        'owner.name' => 'asc',
        'name' => 'asc',
    ]);
});

it('returns a column sort direction', function(){
    $table = fakeTable(new class () extends WiredTable {
        protected function configure(TableConfiguration $configuration): void
        {
            $configuration->multipleSorting();
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

    $table->sort('name');
    expect($table->getSortDirection('name'))->toBe(Sorting::asc);

    $table->sort('name');
    expect($table->getSortDirection('name'))->toBe(Sorting::desc);

    $table->sort('name');
    expect($table->getSortDirection('name'))->toBe(Sorting::none);
});

it('returns a column sort position', function(){
    $table = fakeTable(new class () extends WiredTable {
        protected function configure(TableConfiguration $configuration): void
        {
            $configuration->multipleSorting();
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

    $table->sort('owner.name');
    $table->sort('name');

    expect($table->getSortPosition('name'))->toBe(2);
    expect($table->getSortPosition('owner.name'))->toBe(1);
});

it('applies closure sorting', function(){
    $table = fakeTable(new class () extends WiredTable {
        protected function configure(TableConfiguration $configuration): void
        {
            $configuration->multipleSorting();
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

    $table->sort('name');
    expect($table)->rawQuery()->toBe('select * from "cars" order by "name" asc limit 10 offset 0');


    $table->sort('owner.name');
    expect($table)->rawQuery()->toBe('select * from "cars" order by "name" asc, (select "name" from "users" where "cars"."owner_id" = "users"."id") asc limit 10 offset 0');

})->only();



