<?php

use DefStudio\WiredTables\Configurations\TableConfiguration;

test('defaults', function () {
    $config = new TableConfiguration();

    expect($config->toArray())->toBe([
        'font_size_class' => 'text-sm',
        'text_align_class' => 'text-left',
        'text_color_class' => 'text-gray-800',
        'id_field' => 'id',
        'available_page_sizes' => [
            0 => 10,
            1 => 20,
            2 => 50,
            3 => 100,
            4 => 'all',
        ],
        'default_page_size' => 10,
        'preserve_state' => true,
        'striped' => true,
        'enable_row_dividers' => false,
        'drop_shadow' => false,
        'hover' => false,
        'support_multiple_sorting' => false,
        'group_filters' => true,
        'group_actions' => true,
        'filters_columns' => 1,
        'actions_columns' => 1,
        'always_show_actions' => false,
    ]);
});

it('can disable pagination', function () {
    $config = new TableConfiguration();

    $config->disablePagination();

    expect($config->toArray())->toMatchArray([
        'available_page_sizes' => [],
        'default_page_size' => 'all',
    ]);
});

it('can set compact table mode', function () {
    $config = new TableConfiguration();

    $config->compactTable();

    expect($config->toArray())->toMatchArray([
        'compact_table' => true,
    ]);
});

it('can set default page size', function () {
    $config = new TableConfiguration();

    $config->pageSize(100);

    expect($config->toArray())->toMatchArray([
        'default_page_size' => 100,
    ]);
});

it('can set available pages', function () {
    $config = new TableConfiguration();

    $config->pageSize(100, [1, 2, 100, 150]);

    expect($config->toArray())->toMatchArray([
        'available_page_sizes' => [1, 2, 100, 150],
        'default_page_size' => 100,
    ]);
});

it('can disable row stripes', function () {
    $config = new TableConfiguration();

    $config->striped(false);

    expect($config->toArray())->toMatchArray([
        'striped' => false,
    ]);
});

it('can enable rows hover effect', function () {
    $config = new TableConfiguration();

    $config->hover();

    expect($config->toArray())->toMatchArray([
        'hover' => true,
    ]);
});

it('can set the number of filters columns', function () {
    $config = new TableConfiguration();

    $config->filterSelectorColumns(42);

    expect($config->toArray())->toMatchArray([
        'filters_columns' => 42,
    ]);
});

it('can set the number of actions columns', function () {
    $config = new TableConfiguration();

    $config->actionsSelectorColumns(42);

    expect($config->toArray())->toMatchArray([
        'actions_columns' => 42,
    ]);
});

it('can disable row dividers', function () {
    $config = new TableConfiguration();

    $config->rowDividers(false);

    expect($config->toArray())->toMatchArray([
        'enable_row_dividers' => false,
    ]);
});

it('can enable multiple sorting', function () {
    $config = new TableConfiguration();

    $config->multipleSorting();

    expect($config->toArray())->toMatchArray([
        'support_multiple_sorting' => true,
    ]);
});

it('can enable debug mode', function () {
    enableDebug();

    $config = new TableConfiguration();
    $config->debug();

    expect($config->toArray())->toMatchArray([
        'debug' => true,
    ]);
});

it("can't set debug mode if debug is disabled", function () {
    $config = new TableConfiguration();

    $config->debug();

    expect($config->toArray())->not->toHaveKey('debug');
});

it('can set row_id field', function () {
    $config = new TableConfiguration();

    $config->rowIdField('foo');

    expect($config->toArray())->toMatchArray(['id_field' => 'foo']);
});

it('can set state preserving', function () {
    $config = new TableConfiguration();

    $config->preserveState(false);
    expect($config->toArray())->toMatchArray(['preserve_state' => false]);

    $config->preserveState();
    expect($config->toArray())->toMatchArray(['preserve_state' => true]);
});
