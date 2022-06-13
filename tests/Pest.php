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
            $this->column('Data', 'data->foo');
            $this->column('Not Sortable');
            $this->column('Not Searchable');
        }
    };

    $table->bootedHasConfiguration();
    $table->bootedBuildsQuery();
    $table->bootedHasColumns();
    $table->bootedHasActions();
    $table->bootedHasFilters();
    $table->bootedHasPagination();
    $table->bootedHasFilters();

    return $table;
}

function listFolderFiles($dir): Generator
{
    foreach (scandir($dir) as $file) {
        if ($file[0] == '.') {
            continue;
        }
        if (is_dir("$dir/$file")) {
            foreach (listFolderFiles("$dir/$file") as $infile) {
                yield $infile;
            }
        } else {
            yield "${dir}/${file}";
        }
    }
}

expect()->extend('rawQuery', function () {
    enableDebug();

    $this->value = $this->value->debugQuery();

    return $this;
});
