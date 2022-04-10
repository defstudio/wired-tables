<?php

namespace DefStudio\WiredTables\Exceptions;

class TemplateException extends \Exception
{
    public static function invalidTemplate($key): TemplateException
    {
        return new TemplateException("Template [$key] not found");
    }
}
