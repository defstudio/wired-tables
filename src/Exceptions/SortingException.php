<?php

namespace DefStudio\WiredTables\Exceptions;

class SortingException extends \Exception
{
    public static function autosortNotSupportedForNestedRelations(string $relations): SortingException
    {
        return new SortingException("Auto sorting is not available for nested relations [$relations]");
    }

    public static function relationDoesntExist(string $relation): SortingException
    {
        return new SortingException("Cannot sort by a non-existent relations [$relation]");
    }

    public static function autosortRelationNotSupported(string $relationClass): SortingException
    {
        return new SortingException("Auto sorting is not supported for [$relationClass] relation");
    }

    public static function columnNotFound(string $columnName): SortingException
    {
        return new SortingException("Failed to sort by [$columnName]: Column not found");
    }

    public static function columnNotSortable(string $columnName): SortingException
    {
        return new SortingException("Column [$columnName] is not sortable");
    }
}
