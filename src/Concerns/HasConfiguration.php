<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Configurations\TableConfiguration;
use DefStudio\WiredTables\WiredTable;

/**
 * @mixin WiredTable
 */
trait HasConfiguration
{
    protected TableConfiguration $configuration;

    public function bootedHasConfiguration(): void
    {
        $this->configuration = new TableConfiguration();
        $this->config($this->configuration);
    }

    public function configuration(): TableConfiguration
    {
        return $this->configuration;
    }

    protected function config(TableConfiguration $configuration): void
    {
        // Uses standard options by default
    }
}
