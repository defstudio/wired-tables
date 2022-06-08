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
        'striped' => true,
        'enable_row_dividers' => false,
        'drop_shadow' => false,
        'hover' => false,
        'support_multiple_sorting' => false,
        'filters_columns' => 1,
        'actions_columns' => 1,
        'always_show_actions' => false,
    ]);

    expect($table->configuration()->header->toArray())->toBe([
        'font_size_class' => 'text-xs',
        'text_color_class' => 'text-gray-500',
    ]);
});
