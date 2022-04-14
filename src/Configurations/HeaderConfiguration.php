<?php

namespace DefStudio\WiredTables\Configurations;

use DefStudio\WiredTables\Concerns\HasTextConfiguration;

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
    }
}
