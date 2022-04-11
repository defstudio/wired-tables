<?php

namespace DefStudio\WiredTables\Enums;

enum Config
{
    case debug;

    case font_size_class;
    case text_color_class;
    case text_align_class;

    case is_sortable;
    case support_multiple_sorting;
    case enable_row_dividers;
    case striped;
    case available_page_sizes;
    case default_page_size;

    case name;
    case db_column;
    case format_closure;
}
