<?php

namespace DefStudio\WiredTables\Elements;

use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Configurations\Configuration;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;
use Hash;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Str;

class Column extends Configuration implements Arrayable
{
    use HasTextConfiguration;

    private Model $model;
    private string $id;

    public function __construct(
        WiredTable $table,
        string $name,
        string|null $dbColumn = null
    ) {
        $this->initDefaults();

        $this->set(Config::name, $name)
            ->set(Config::db_column, $dbColumn)
            ->set(Config::id, Hash::make($this->name() . $this->dbColumn().$table->id));
    }

    protected function initDefaults(): void
    {
        $this
            ->set(Config::is_sortable, false);
    }

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function name(): string
    {
        return $this->get(Config::name);
    }

    public function dbColumn(): string
    {
        return $this->get(Config::db_column, Str::of($this->name())->snake()->toString());
    }

    public function id(): string
    {
        return $this->get(Config::id);
    }

    public function sortable(callable $sortClosure = null): static
    {
        return $this->set(Config::is_sortable, true)
            ->set(Config::sort_closure, $sortClosure);
    }

    /**
     * @param callable(Column $column) $formatClosure
     *
     * @return $this
     */
    public function format(callable $formatClosure): static
    {
        return $this->set(Config::format_closure, $formatClosure);
    }

    public function toArray(): array
    {
        $config = $this->config;

        $config['db_column'] = $this->dbColumn();

        return $config;
    }

    public function value(): mixed
    {
        return data_get($this->model, $this->dbColumn());
    }

    public function render(): HtmlString
    {
        $value = $this->value();

        if (! empty($formatClosure = $this->get(Config::format_closure))) {
            $value = $formatClosure($this->model, $value, $this);
        }

        return new HtmlString($value);
    }

    public function isRelationship(): bool
    {
        return  Str::of($this->dbColumn())->contains('.');
    }

    public function getRelationship(): string
    {
        return Str::of($this->dbColumn())->beforeLast('.');
    }

    public function getField(): string
    {
        return Str::of($this->dbColumn())->afterLast('.');
    }
}
