<?php

namespace DefStudio\WiredTables\Elements;

use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Configurations\Configuration;
use DefStudio\WiredTables\Enums\Config;
use Illuminate\Contracts\Support\Arrayable;
use Str;

class Column extends Configuration implements Arrayable
{
    use HasTextConfiguration;

    public function __construct(
        string $name,
        string|null $dbColumn = null
    ) {
        $this->initDefaults();
        $this->set(Config::name, $name)
            ->set(Config::db_column, $dbColumn);
    }

    protected function initDefaults(): void
    {
        $this
            ->set(Config::is_sortable, false);
    }

    public function name(): string
    {
        return $this->get(Config::name);
    }

    public function dbColumn(): string
    {
        return $this->get(Config::db_column, Str::of($this->name())->snake()->toString());
    }

    public function sortable(): static
    {
        return $this->set(Config::is_sortable, true);
    }

    public function toArray(): array
    {
        $config = $this->config;

        $config['db_column'] = $this->dbColumn();

        return $config;
    }
}
