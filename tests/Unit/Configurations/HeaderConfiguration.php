<?php

use DefStudio\WiredTables\Configurations\HeaderConfiguration;
use DefStudio\WiredTables\Configurations\TableConfiguration;

test('defaults', function () {
    $config = new HeaderConfiguration(new TableConfiguration());

    expect($config->toArray())->toBe([
        'font_size_class' => 'text-xs',
        'text_color_class' => 'text-gray-500',
        'dark_mode' => false,
    ]);
});
