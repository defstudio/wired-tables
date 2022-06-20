<?php

/** @noinspection PhpUnused */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\WiredTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin WiredTable
 */
trait HasCache
{
    protected function getFromCache(string $key, mixed $default = null): mixed
    {
        $user = Auth::user();

        if (!$user) {
            return $default;
        }

        return Cache::get("$this->tableSlug-$user->id-$key", $default);
    }

    protected function storeInCache(string $key, mixed $value): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        Cache::put("$this->tableSlug-$user->id-$key", $value);
    }
}
