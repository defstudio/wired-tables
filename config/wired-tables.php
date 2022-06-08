<?php

// config for DefStudio/WiredTables

return [

    /**
     * CSS framework for table styling, available: tailwind_3/bootstrap_4
     */
    'style' => 'tailwind_3',

    'defaults' => [
        'table' => [
            'row_id_field' => 'id',
            'page_size' => 10,
            'striped' => true,
            'row_dividers' => false,
            'drop_shadow' => false,
            'hover' => false,
            'multiple_sorting' => false,
            'debug' => false,
            'filter_selector_columns' => 1,
            'actions_selector_columns' => 1,
            'always_show_actions' => false,
        ],
    ],
];
