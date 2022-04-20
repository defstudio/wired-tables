<?php

namespace DefStudio\WiredTables\Exceptions;

class FilterException extends \Exception
{
    public static function locked(): FilterException
    {
        return new FilterException("Filters can be added only inside WiredTable::filters() method");
    }

    public static function duplicated(string $name): FilterException
    {
        return new FilterException("Duplicated filter [$name]");
    }

    public static function notFound(string $name): FilterException
    {
        return new FilterException("Filter not found: [$name]");
    }

    public static function noHandlerSet(string $name): FilterException
    {
        return new FilterException("No handler set for filter: [$name]");
    }
}
