<?php

namespace DefStudio\WiredTables\Elements;

use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Configurations\Configuration;
use DefStudio\WiredTables\Enums\Config;
use Str;

class Column extends Configuration
{
    use HasTextConfiguration;

    public function __construct(
        private string $name,
        private string|null $dbColumn = null
    ) {
        $this->initDefaults();
    }

    protected function initDefaults(): void
    {
        $this->set(Config::is_sortable, false);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function dbColumn(): string
    {
        return $this->dbColumn ?? Str::of($this->name)->snake();
    }

    public function sortable(): static
    {
        return $this->set(Config::is_sortable, true);
    }
}
