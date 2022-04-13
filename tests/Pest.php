<?php /** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\WiredTables\Tests\TestCase;
use DefStudio\WiredTables\WiredTable;

uses(TestCase::class)->in(__DIR__ . "/Unit");
uses(TestCase::class)->in(__DIR__ . "/Feature");

function fakeTable(WiredTable $table): WiredTable
{
    $table->bootHasConfiguration();
    $table->bootBuildsQuery();
    $table->bootHasColumns();
    $table->mountHasPagination();
    return $table;
}

expect()->extend('rawQuery', function () {
    Config::set('app.debug', true);

    $this->value = $this->value->debugQuery();

    return $this;
});
