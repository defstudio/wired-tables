<?php

it('can compute plain query strings', function () {
    $table = fakeTable();

    expect($table->queryString())->toBe([
        "search" => ['except' => '', 'as' => 'search'],
        "sorting" => ['except' => [], 'as' => 'sort'],
        "filterValues" => ['except' => '', 'as' => 'filters'],
    ]);
});

it('can compute slugged query strings', function () {
    $table = fakeTable();
    $table->_cachedSlug = 'foo';


    expect($table->queryString())->toBe([
        "search" => ['except' => '', 'as' => 'foo.search'],
        "sorting" => ['except' => [], 'as' => 'foo.sort'],
        "filterValues" => ['except' => '', 'as' => 'foo.filters'],
    ]);
});
