<?php

use DefStudio\WiredTables\Configurations\TableConfiguration;

test('configuration is booted', function () {
    $table = fakeTable();

    expect($table->configuration())
        ->toBeInstanceOf(TableConfiguration::class);
});

test('default configuration is loaded', function () {
    $table = fakeTable();

    expect($table->configuration()->toArray())->toBe([
        'support_multiple_sorting' => false,
        'enable_row_dividers' => true,
        'striped' => true,
        'available_page_sizes' => [10, 20, 50, 100, 'all'],
        'default_page_size' => 10,
        'font_size_class' => 'text-sm',
        'text_align_class' => 'text-left',
        'text_color_class' => 'text-gray-800',
    ]);

    expect($table->configuration()->header->toArray())->toBe([
        'font_size_class' => 'text-xs',
        'text_color_class' => 'text-gray-500'
    ]);
});
