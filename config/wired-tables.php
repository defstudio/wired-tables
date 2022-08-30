<?php

// config for DefStudio/WiredTables

return [

    /**
     * CSS framework for table styling, available: tailwind_3/bootstrap_4
     */
    'style' => 'tailwind_3',

    'date_format' => 'Y-m-d',

    'defaults' => [
        'table' => [
            'row_id_field' => 'id',
            'page_size' => 10,
            'preserve_state' => true,
            'striped' => true,
            'row_dividers' => false,
            'drop_shadow' => false,
            'hover' => false,
            'multiple_sorting' => false,
            'debug' => false,
            'group_filters' => true,
            'group_actions' => true,
            'filter_selector_columns' => 1,
            'actions_selector_columns' => 1,
            'always_show_actions' => false,
        ],
        'header' => [
            'dark' => false,
        ],
    ],
];
