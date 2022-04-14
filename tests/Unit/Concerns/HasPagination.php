<?php /** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\WiredTables\Exceptions\PaginationException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

test('pagination is mounted', function () {
    $table = fakeTable();

    expect($table->pageSize)->toBe(10);
});

test('page is reset when page size changes', function () {

    $table = fakeTable();

    $table->page = 2;
    $table->updatedPageSize();

    expect($table->page)->toBe(1);
});

it('can change page size', function(){
    $table = fakeTable();

    $table->setPageSize(20);;

    expect($table->pageSize)->toBe(20);
});

it('prevents invalid page sizes', function(){
    $table = fakeTable();

    expect(fn() => $table->setPageSize(42))->toThrow(PaginationException::class);
});

it('tells if pagination is enabled', function(){
    $table = fakeTable();

    expect($table->paginationEnabled())->toBeTrue();

    $table->configuration()->disablePagination();

    expect($table->paginationEnabled())->toBeFalse();
});
