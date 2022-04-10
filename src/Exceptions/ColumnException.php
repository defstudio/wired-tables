<?php

namespace DefStudio\WiredTables\Exceptions;

class ColumnException extends \Exception
{
    public static function noColumnDefined(string $tableClass): ColumnException
    {
        return new ColumnException("No column defined for table [$tableClass]");
    }

    public static function locked(): ColumnException
    {
        return new ColumnException("Columns can be added only inside WiredTable::columns() method");
    }
}
