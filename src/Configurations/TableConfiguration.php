<?php

/** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Configurations;

use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Enums\Config;

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
            ->set(Config::support_multiple_sorting, false)
            ->rowIdField('id')
            ->rowDividers()
            ->striped()
            ->pageSize(10, )
            ->alwaysShowBulkActions(false)
            ->fontSm()
            ->textLeft()
            ->textColorClass('text-gray-800');
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

    public function rowDividers(bool $enable = true): static
    {
        return $this->set(Config::enable_row_dividers, $enable);
    }

    public function multipleSorting(bool $enable = true): static
    {
        return $this->set(Config::support_multiple_sorting, $enable);
    }

    public function alwaysShowBulkActions(bool $enable = true): static
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
