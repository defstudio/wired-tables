<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\WiredTables\Configurations\TableConfiguration;
use DefStudio\WiredTables\Tests\TestCase;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

uses(TestCase::class)->in(__DIR__ . "/Unit");
uses(TestCase::class)->in(__DIR__ . "/Feature");

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
    $table->mountHasPagination();

    return $table;
}

expect()->extend('rawQuery', function () {
    Config::set('app.debug', true);

    $this->value = $this->value->debugQuery();

    return $this;
});
