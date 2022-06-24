<?php

/** @noinspection PhpUnused */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @mixin WiredTable
 */
trait PreservesState
{
    public string $_stateKey;

    public function mountPreservesState(): void
    {
        $this->_stateKey = $this->slug ?: Str::of(url()->current())->slug();
    }

    private function getStateKey(Authenticatable $user, string $key): string
    {
        return "$this->_stateKey-{$user->getAuthIdentifier()}-state-$key";
    }

    protected function getState(string $key, mixed $default = null): mixed
    {
        if (!$this->config(Config::preserve_state, false)) {
            return $default;
        }

        $user = Auth::user();

        if (!$user) {
            return $default;
        }

        return Cache::get($this->getStateKey($user, $key), $default);
    }

    protected function storeState(string $key, mixed $value): void
    {
        if (!$this->config(Config::preserve_state, false)) {
            return;
        }

        $user = Auth::user();

        if (!$user) {
            return;
        }

        Cache::put($this->getStateKey($user, $key), $value);
    }
}
