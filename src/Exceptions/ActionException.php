<?php

namespace DefStudio\WiredTables\Exceptions;

class ActionException extends \Exception
{
    public static function locked(): ActionException
    {
        return new ActionException("Actions can be added only inside WiredTable::actions() method");
    }

    public static function duplicatedAction(string $name): ActionException
    {
        return new ActionException("Duplicated action [$name]");
    }

    public static function methodNotFound(string $name): ActionException
    {
        return new ActionException("Method not found for action [$name]");
    }
}
