<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\WiredTables\Exceptions\PaginationException;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\actingAs;

test('pagination is mounted', function () {
    $table = fakeTable();

    expect($table->pageSize)->toBe(10);
});

test('page is reset when page size changes', function () {
    $table = fakeTable();

    $table->getPage() = 2;
    $table->updatedPageSize();

    expect($table->getPage())->toBe(1);
});

it('can change page size', function () {
    $table = fakeTable();

    $table->setPageSize(20);
    ;

    expect($table->pageSize)->toBe(20);
});

it('prevents invalid page sizes', function () {
    $table = fakeTable();

    expect(fn () => $table->setPageSize(42))->toThrow(PaginationException::class);
});

it('tells if pagination is enabled', function () {
    $table = fakeTable();

    expect($table->paginationEnabled())->toBeTrue();

    $table->configuration()->disablePagination();

    expect($table->paginationEnabled())->toBeFalse();
});

test('cached page size is mounted', function () {
    actingAs(new User(['id' => 42]));

    Cache::put("httplocalhost-42-state-page-size", 50);

    $table = fakeTable();

    expect($table->pageSize)->toBe(50);
});

test('cached page size is updated', function () {
    actingAs(new User(['id' => 42]));



    $table = fakeTable();
    $table->setPageSize(20);

    expect(Cache::get("httplocalhost-42-state-page-size"))->toBe(20);
});
