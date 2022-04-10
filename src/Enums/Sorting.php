<?php

namespace DefStudio\WiredTables\Enums;

enum Sorting: string
{
    case none = 'none';
    case asc = 'asc';
    case desc = 'desc';

    public function next(): Sorting
    {
        return match ($this) {
            self::none => self::asc,
            self::asc => self::desc,
            self::desc => self::none,
        };
    }
}
