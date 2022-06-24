<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Configurations;

use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\ConfigException;
use Illuminate\Support\Str;

class HeaderConfiguration extends Configuration
{
    use HasTextConfiguration;

    public function __construct(TableConfiguration $table)
    {
        $this->parentConfiguration = $table;
        $this->initDefaults();
    }

    private function initDefaults(): void
    {
        $this->fontXs()
            ->textColorClass('text-gray-500');

        collect(config('wired-tables.defaults.header'))->each(function ($value, $key) {
            $configMethod = Str::of($key)->camel()->toString();
            if (!method_exists($this, $configMethod)) {
                throw ConfigException::unsupportedConfig("wired-tables.defaults.header.$key");
            }

            $this->$configMethod($value);
        });
    }

    public function dark($enable = true): static
    {
        $this->set(Config::dark_mode, $enable);

        if ($enable) {
            $this->textColorClass('text-gray-50');
        }

        return $this;
    }
}
