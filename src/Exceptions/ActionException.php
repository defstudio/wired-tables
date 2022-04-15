<?php

namespace DefStudio\WiredTables\Exceptions;

class ActionException extends \Exception
{
    public static function locked(): ActionException
    {
        return new ActionException("Actions can be added only inside WiredTable::actions() method");
    }

    public static function duplicated(string $name): ActionException
    {
        return new ActionException("Duplicated action [$name]");
    }

    public static function methodNotFound(string $name): ActionException
    {
        return new ActionException("Method not found for action [$name]");
    }

    public static function handlerNotFound(string $name): ActionException
    {
        return new ActionException("Handler not found for action [$name]");
    }

    public static function notFound(string $name): ActionException
    {
        return new ActionException("Action not found: [$name]");
    }
}
