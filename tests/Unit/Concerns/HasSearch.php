<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection SqlDialectInspection */

/** @noinspection SqlResolve */

use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('tells if search is enabled', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name')->searchable();
        }
    });

    expect($table->isSearchable())->toBeTrue();

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

    expect($table->isSearchable())->toBeFalse();
});

it('can search in a column', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name')->searchable();
            $this->column('Owner', 'owner.name');
        }
    });

    $table->search = 'foo';

    expect($table)->rawQuery()->toBe('select * from "cars" where ("name" like \'%foo%\') limit 10 offset 0');
});

it('can search in a json column', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name')->searchable();
            $this->column('Owner', 'owner.name');
            $this->column('Foo', 'data->foo->bar')->searchable();
        }
    });

    $table->search = 'foo';

    expect($table)->rawQuery()->toBe('select * from "cars" where ("name" like \'%foo%\' or json_extract("data", \'$."foo"."bar"\') like \'%foo%\') limit 10 offset 0');
});

it('can search in two columns', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name')->searchable();
            $this->column('Color')->searchable();
            $this->column('Owner', 'owner.name');
        }
    });

    $table->search = 'foo';

    expect($table)->rawQuery()->toBe('select * from "cars" where ("name" like \'%foo%\' or "color" like \'%foo%\') limit 10 offset 0');
});

it('can search in a first level relationships column', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Color');
            $this->column('Owner', 'owner.name')->searchable();
        }
    });

    $table->search = 'foo';

    expect($table)->rawQuery()->toBe('select * from "cars" where (exists (select * from "users" where "cars"."user_id" = "users"."id" and "name" like \'%foo%\')) limit 10 offset 0');
});

it('can apply a closure search', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Color')->searchable(function (Builder $query, string $term) {
                $query->orWhere('color', $term);
            });
            $this->column('Owner', 'owner.name');
        }
    });

    $table->search = 'foo';

    expect($table)->rawQuery()->toBe('select * from "cars" where ("color" = \'foo\') limit 10 offset 0');
});

it('can search without breaking the query', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query()->where('position', '>', 15);
        }

        protected function columns(): void
        {
            $this->column('Name')->searchable();
            $this->column('Color')->searchable();
            $this->column('Owner', 'owner.name');
        }
    });

    $table->search = 'foo';

    expect($table)->rawQuery()->toBe('select * from "cars" where "position" > 15 and ("name" like \'%foo%\' or "color" like \'%foo%\') limit 10 offset 0');
});

it('can apply an autosearch to a column', function () {
    enableDebug();
    $table = fakeTable();
    $query = Car::query();

    $table->applyAutoSearchToColumn($table->getColumn('Name'), $query, 'foo');

    expect($table->debugQuery($query))
        ->toBe('select * from "cars" where "name" like \'%foo%\'');
});

it('can apply an autosearch to a relation column', function () {
    enableDebug();
    $table = fakeTable();
    $query = Car::query();

    $table->applyAutoSearchToColumn($table->getColumn('Owner'), $query, 'foo');

    expect($table->debugQuery($query))
        ->toBe('select * from "cars" where exists (select * from "users" where "cars"."user_id" = "users"."id" and "name" like \'%foo%\')');
});
