<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

/** @noinspection SqlDialectInspection */

use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('can tell if rows selector should be shown', function () {
    $table = fakeTable();
    expect($table->shouldShowRowsSelector())->toBeFalse();

    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('test');
        }

        protected function actions(): void
        {
            $this->action('global action');
            $this->action('rows action')->withRowSelection();
        }
    });

    expect($table->shouldShowRowsSelector())->toBeTrue();
});

it('can return selected ids', function () {
    $table = fakeTable();
    $table->selectRows([1, 3, 6]);

    expect($table->selectedIds())->toBe([1, 3, 6]);
});

it('can select rows', function () {
    $table = fakeTable();
    $table->selectRows([1, 3, 9]);

    expect($table->selection)->toBe([
        1 => true,
        3 => true,
        9 => true,
    ]);
});

it('can select visible rows', function () {
    Car::factory(12)->create();
    $table = fakeTable();

    $table->selectVisibleRows();

    expect($table->selectedIds())->toBe([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
});

it('can unselect all', function () {
    $table = fakeTable();
    $table->selectRows([1, 3, 9]);

    $table->unselectAllRows();

    expect($table->selectedIds())->toBeEmpty();
});

it('can retrieve a row id', function () {
    $table = fakeTable();

    $car = Car::make();
    $car->id = 42;

    expect($table->getRowId($car))->toBe(42);
});

it('can retrieve a row custom id', function () {
    $table = fakeTable();
    $table->configuration()->rowIdField('uuid');

    $car = Car::make();
    $car->id = 42;
    $car->uuid = 666;

    expect($table->getRowId($car))->toBe(666);
});

it('selects all rows when "all selected" checkbox is checked', function () {
    Car::factory(12)->create();
    $table = fakeTable();
    $table->updatedAllSelected(true);

    expect($table->selectedIds())->toBe([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
});

it('unselects all rows when "all selected" checkbox is unchecked', function () {
    $table = fakeTable();

    $table->updatedAllSelected(false);

    expect($table->selectedIds())->toBeEmpty();
});

it('disables "all selected" and "all pages selected" when a row is unselected', function () {
    $table = fakeTable();
    $table->allSelected = true;
    $table->allPagesSelected = true;

    $table->updatedSelection(false);

    expect($table)
        ->allSelected->toBeFalse()
        ->allPagesSelected->toBeFalse();
});

it("sets 'all selected' as checked when all row in a page are selected", function () {
    Car::factory(12)->create();
    $table = fakeTable();

    foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $rowId) {
        $table->selection[$rowId] = true;
        $table->updatedSelection(true);

        expect($table)
            ->allSelected->toBeFalse();
    }

    $table->selection[10] = true;
    $table->updatedSelection(true);

    expect($table)
        ->allSelected->toBeTrue();
});

it("applies row selection", function () {
    Config::set('app.debug', true);
    $table = fakeTable();

    $table->selectRows([1, 3, 9]);

    expect($table->debugQuery($table->selectedRows()))
        ->toBe('select * from "cars" where "id" in (1, 3, 9)');
});
