<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\WiredTables\Configurations\TableConfiguration;
use DefStudio\WiredTables\Tests\TestCase;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

uses(TestCase::class)->in(__DIR__ . "/Unit");
uses(TestCase::class)->in(__DIR__ . "/Feature");

function enableDebug(): void
{
    Config::set('app.debug', true);
}

function fakeTable(WiredTable $table = null): WiredTable
{
    $table ??= new class () extends WiredTable {
        protected function configure(TableConfiguration $configuration): void
        {
        }

        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function actions(): void
        {
            $this->action('action one');
        }

        protected function filters(): void
        {
            $this->filter('Brand')
                ->select([
                    'lotus' => 'Lotus',
                    'lamborghini' => 'Lamborghini',
                    'ferrari' => 'Ferrari',
                ])->handle(fn (Builder $query, string $brand) => $query->where('brand', $brand));
        }

        public function actionOne(): void
        {
        }

        protected function columns(): void
        {
            $this->column('Name')->sortable()->searchable();
            $this->column('Owner', 'owner.name')->sortable()->searchable();
            $this->column('Not Sortable');
            $this->column('Not Searchable');
        }
    };

    $table->bootHasConfiguration();
    $table->bootBuildsQuery();
    $table->bootHasColumns();
    $table->bootHasActions();
    $table->bootHasFilters();
    $table->mountHasPagination();
    $table->mountHasFilters();

    return $table;
}

expect()->extend('rawQuery', function () {
    enableDebug();

    $this->value = $this->value->debugQuery();

    return $this;
});
