<?php

namespace DefStudio\WiredTables\Enums;

enum Config
{
    case debug;

    case font_size_class;
    case text_color_class;
    case text_align_class;

    case id_field;

    case dark_mode;
    case drop_shadow;
    case support_multiple_sorting;
    case default_sorting;
    case enable_row_dividers;
    case preserve_state;
    case striped;
    case available_page_sizes;
    case default_page_size;
    case always_show_actions;
    case hover;

    case id;
    case name;
    case db_column;
    case format_closure;
    case is_searchable;
    case is_json_column;
    case is_sortable;
    case sort_closure;
    case search_closure;
    case view;
    case view_params;
    case event;
    case url;
    case url_target;
    case date_format;
    case wrapText;

    case method;
    case handler;
    case hidden;

    case key;
    case display_on_column;
    case type;
    case options;
    case placeholder;

    case with_row_selection;
    case actions_columns;
    case group_actions;
    case filters_columns;
    case group_filters;
}
