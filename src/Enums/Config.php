<?php

namespace DefStudio\WiredTables\Enums;

enum Config
{
    case debug;

    case font_size_class;
    case text_color_class;
    case text_align_class;

    case id_field;

    case support_multiple_sorting;
    case enable_row_dividers;
    case striped;
    case available_page_sizes;
    case default_page_size;
    case always_show_actions;

    case id;
    case name;
    case db_column;
    case format_closure;
    case is_searchable;
    case is_sortable;
    case sort_closure;
    case search_closure;

    case method;
    case handler;
    case hidden;

    case with_row_selection;
}
