<?php

namespace DefStudio\WiredTables\Configurations;

use DefStudio\WiredTables\Enums\Config;

abstract class Configuration
{
    protected ?Configuration $parentConfiguration = null;
    protected array $config = [];

    public function setParentConfiguration(Configuration $parent): static
    {
        $this->parentConfiguration = $parent;

        return $this;
    }

    public function set(Config $key, mixed $value): static
    {
        $this->config[$key->name] = $value;

        return $this;
    }

    public function get(Config $key, mixed $default = null): mixed
    {
        return data_get($this->config, $key->name, $this->parentConfiguration?->get($key)) ?? $default;
    }

    public function dump(): void
    {
        dump($this->toArray());
    }

    public function toArray(): array
    {
        return $this->config;
    }
}
