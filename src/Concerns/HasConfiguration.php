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
        $this->setup($this->configuration);
    }

    public function configuration(): TableConfiguration
    {
        return $this->configuration;
    }

    protected function setup(TableConfiguration $options): void
    {
        // Uses standard options by default
    }
}
