<?php

/** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Configurations;

use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\ConfigException;
use Illuminate\Support\Str;

class TableConfiguration extends Configuration
{
    use HasTextConfiguration;

    public HeaderConfiguration $header;

    public function __construct()
    {
        $this->header = new HeaderConfiguration($this);
        $this->initDefaults();
    }

    private function initDefaults(): void
    {
        $this
            ->fontSm()
            ->textLeft()
            ->textColorClass(match (config('wired-tables.style')) {
                'tailwind_3' => 'text-gray-800',
                'tailwind_3_prefixed' => 'tailwind_3text-dark',
                default => throw ConfigException::invalidValue('style', \config('wired-tables.style', ''))
            });

        collect(config('wired-tables.defaults.table'))->each(function ($value, $key) {
            $configMethod = Str::of($key)->camel()->toString();
            if (!method_exists($this, $configMethod)) {
                throw ConfigException::unsupportedConfig("wired-tables.defaults.table.$key");
            }

            $this->$configMethod($value);
        });
    }

    public function rowIdField(string $field): static
    {
        return $this->set(Config::id_field, $field);
    }

    public function disablePagination(): static
    {
        return $this->set(Config::available_page_sizes, [])
            ->set(Config::default_page_size, 'all');
    }

    public function pageSize(int|string $default, array $available = [10, 20, 50, 100, 'all']): static
    {
        return $this->set(Config::available_page_sizes, $available)
            ->set(Config::default_page_size, $default);
    }

    public function striped(bool $enable = true): static
    {
        return $this->set(Config::striped, $enable);
    }

    public function dropShadow(bool $enable = true): static
    {
        return $this->set(Config::drop_shadow, $enable);
    }

    public function hover(bool $enable = true): static
    {
        return $this->set(Config::hover, $enable);
    }

    public function filterSelectorColumns(int $count): static
    {
        return $this->set(Config::filters_columns, $count);
    }

    public function actionsSelectorColumns(int $count): static
    {
        return $this->set(Config::actions_columns, $count);
    }

    public function rowDividers(bool $enable = true): static
    {
        return $this->set(Config::enable_row_dividers, $enable);
    }

    public function multipleSorting(bool $enable = true): static
    {
        return $this->set(Config::support_multiple_sorting, $enable);
    }

    public function alwaysShowActions(bool $enable = true): static
    {
        $this->set(Config::always_show_actions, $enable);

        return $this;
    }

    public function debug(bool $enable = true): static
    {
        if (config('app.debug')) {
            $this->set(Config::debug, $enable);
        }

        return $this;
    }
}
