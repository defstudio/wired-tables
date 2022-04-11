<?php

namespace DefStudio\WiredTables\Configurations;

use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Enums\Config;

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
        $this
            ->set(Config::support_multiple_sorting, false)
            ->fontXs()
            ->textLeft()
            ->textColorClass('text-gray-500');
    }

}
