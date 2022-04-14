<?php

use DefStudio\WiredTables\Configurations\TableConfiguration;

test('defaults', function () {
    $config = new TableConfiguration();

    expect($config->toArray())->toBe([
        'support_multiple_sorting' => false,
        'enable_row_dividers' => true,
        'striped' => true,
        'available_page_sizes' => [
            0 => 10,
            1 => 20,
            2 => 50,
            3 => 100,
            4 => 'all',
        ],
        'default_page_size' => 10,
        'font_size_class' => 'text-sm',
        'text_align_class' => 'text-left',
        'text_color_class' => 'text-gray-800',
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
    $config = new TableConfiguration();

    Config::set('app.debug', true);
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
