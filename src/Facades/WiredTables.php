<?php

namespace DefStudio\WiredTables\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DefStudio\WiredTables\WiredTables
 */
class WiredTables extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wired-tables';
    }
}
