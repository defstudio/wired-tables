<?php

namespace DefStudio\WiredTables\Exceptions;

use Exception;

class SearchException extends Exception
{
    public static function autosearchNotSupportedForNestedRelations(string $relations): SearchException
    {
        return new SearchException("Auto search is not available for nested relations [$relations]");
    }

    public static function relationDoesntExist(string $relation): SearchException
    {
        return new SearchException("Cannot search by a non-existent relation [$relation]");
    }

    public static function autosearchRelationNotSupported(string $relationClass): SortingException
    {
        return new SortingException("Auto search is not supported for [$relationClass] relations");
    }
}
