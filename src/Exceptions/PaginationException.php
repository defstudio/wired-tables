<?php

namespace DefStudio\WiredTables\Exceptions;

class PaginationException extends \Exception
{
    public static function unallowedSize(int|string $size): PaginationException
    {
        return new PaginationException("Unallowed page size: [$size]");
    }
}
