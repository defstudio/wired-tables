<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Elements;

use Closure;
use DefStudio\WiredTables\Configurations\Configuration;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\ActionException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Action extends Configuration implements Arrayable
{
    private WiredTable $table;

    public function __construct(WiredTable $table, string $name, string|null $method = null)
    {
        $this->table = $table;

        $this->initDefaults();

        $this->set(Config::name, $name)
            ->set(Config::method, $method);
    }

    protected function initDefaults(): void
    {
        $this->withRowSelection(false);
    }

    public function withRowSelection($enable = true): static
    {
        return $this->set(Config::with_row_selection, $enable);
    }

    public function requiresRowsSelection(): bool
    {
        return $this->get(Config::with_row_selection, false);
    }

    public function hidden(Closure|bool $when = true): static
    {
        return $this->set(Config::hidden, $when);
    }

    public function handle(Closure $handler): static
    {
        return $this->set(Config::handler, $handler);
    }

    public function name(): string
    {
        return $this->get(Config::name);
    }

    public function methodArguments(): Collection
    {
        $arguments = [];

        $handler = $this->get(Config::handler);
        if (is_callable($handler)) {
            $arguments[] = $this->name();
        }

        return collect($arguments);
    }

    public function method(): string
    {
        $handler = $this->get(Config::handler);
        if (is_callable($handler)) {
            return "handleAction";
        }

        $method = $this->get(Config::method);

        if ($method) {
            return $method;
        }

        if (method_exists($this->table, $camel = Str::of($this->name())->camel())) {
            return $camel;
        }

        if (method_exists($this->table, $snake = Str::of($this->name())->snake())) {
            return $snake;
        }

        throw ActionException::methodNotFound($this->name());
    }

    public function processHandler(...$args): void
    {
        $handler = $this->get(Config::handler);

        if (!is_callable($handler)) {
            throw ActionException::handlerNotFound($this->name());
        }

        $handler(...$args);
    }

    public function toArray(): array
    {
        $config = $this->config;

        $config['method'] = $this->method();

        if ($config['method'] === 'handleAction') {
            unset($config['method']);
        }

        return $config;
    }

    public function isVisible(): bool
    {
        if (!empty($hidden = $this->get(Config::hidden))) {
            if (is_callable($hidden)) {
                $hidden = $hidden();
            }

            if ($hidden) {
                return false;
            }
        }

        if ($this->table->config(Config::always_show_actions)) {
            return true;
        }

        if (!$this->requiresRowsSelection()) {
            return true;
        }

        if (!empty($this->table->selection)) {
            return true;
        }

        return false;
    }
}
