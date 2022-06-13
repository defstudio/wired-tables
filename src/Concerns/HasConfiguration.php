<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Configurations\TableConfiguration;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/**
 * @mixin WiredTable
 */
trait HasConfiguration
{
    protected TableConfiguration $configuration;

    public function bootHasConfiguration(): void
    {
        $this->configuration = new TableConfiguration();
        $this->configure($this->configuration);
    }

    public function configuration(): TableConfiguration
    {
        return $this->configuration;
    }

    protected function configure(TableConfiguration $configuration): void
    {
        // Uses standard options by default
    }

    public function config(Config $key, mixed $default = null): mixed
    {
        return $this->configuration()->get($key, $default);
    }

    public function headerConfig(Config $key, mixed $default = null): mixed
    {
        return $this->configuration()->header->get($key, $default);
    }
}
