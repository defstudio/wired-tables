<?php

namespace DefStudio\WiredTables\Exceptions;

class SortingException extends \Exception
{
    public static function autosortNotSupportedForNestedRelations(string $relations): SortingException
    {
        return new SortingException("Auto sorting is not available for nested relationships [$relations]");
    }

    public static function relationDoesntExist(string $relation): SortingException
    {
        return new SortingException("Cannot sort by a non-existent relationship [$relation]");
    }

    public static function autosortRelationNotSupported(string $relationClass): SortingException
    {
        return new SortingException("Auto sorting is not supported for [$relationClass] relationships");
    }
}
