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

    case name;
    case db_column;
}
