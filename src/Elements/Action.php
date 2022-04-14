<?php

namespace DefStudio\WiredTables\Elements;

use Closure;
use DefStudio\WiredTables\Configurations\Configuration;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\ActionException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Contracts\Support\Arrayable;
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

    public function hidden(Closure|bool $when){
        //TODO
    }

    public function name(): string
    {
        return $this->get(Config::name);
    }

    public function method(): string
    {
        $method = $this->get(Config::method);

        if($method){
            return $method;
        }

        if(method_exists($this->table, $camel = Str::of($this->name())->camel())){
            return $camel;
        }

        if(method_exists($this->table, $snake = Str::of($this->name())->snake())){
            return $snake;
        }

        throw ActionException::methodNotFound($this->name());
    }


    public function toArray(): array
    {
        $config = $this->config;

        $config['method'] = $this->method();

        return $config;
    }
}
