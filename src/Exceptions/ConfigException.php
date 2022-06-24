<?php

namespace DefStudio\WiredTables\Exceptions;

class ConfigException extends \Exception
{
    public static function invalidValue(string $key, string $value): ConfigException
    {
        return new ConfigException("[$value] is not a valid value for config [wired-tables.$key]");
    }

    public static function unsupportedConfig(string $key): ConfigException
    {
        return new ConfigException("[$key] is not a valid config key");
    }
}
