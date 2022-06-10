<?php

/** @noinspection PhpUnused */

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace DefStudio\WiredTables\Elements;

use Closure;
use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Configurations\Configuration;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Enums\Sorting;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Column extends Configuration implements Arrayable
{
    use HasTextConfiguration;

    private Model $model;

    public function __construct(WiredTable $table, string $name, string|null $dbColumn = null)
    {
        $this->initDefaults();

        $this->set(Config::name, $name)
            ->set(Config::db_column, $dbColumn)
            ->set(Config::id, md5($this->name() . $this->dbColumn() . $table->id));
    }

    protected function initDefaults(): void
    {
        $this->set(Config::is_sortable, false);
        $this->set(Config::is_searchable, false);
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

    public function isSortable(): bool
    {
        return $this->get(Config::is_sortable, false);
    }

    public function hasSortClosure(): bool
    {
        return is_callable($this->get(Config::sort_closure));
    }

    public function applySortClosure(Builder $query, Sorting $dir): void
    {
        $this->get(Config::sort_closure)($query, $dir);
    }

    public function searchable(callable $searchClosure = null): static
    {
        return $this->set(Config::is_searchable, true)
            ->set(Config::search_closure, $searchClosure);
    }

    public function isSearchable(): bool
    {
        return $this->get(Config::is_searchable);
    }

    public function hasSearchClosure(): bool
    {
        return is_callable($this->get(Config::search_closure));
    }

    public function applySearchClosure(Builder $query, string $term): void
    {
        $this->get(Config::search_closure)($query, $term);
    }

    /**
     * @param Closure(mixed $value, Model $model, Column $column): string $formatClosure
     *
     * @return static
     */
    public function format(Closure $formatClosure): static
    {
        return $this->set(Config::format_closure, $formatClosure);
    }

    public function toArray(): array
    {
        $config = $this->config;

        $config['db_column'] = $this->dbColumn();
        $config['field'] = $this->getField();
        $config['is_relation'] = $this->isRelation();
        $config['relation'] = $this->getRelation();


        return $config;
    }

    public function value(): mixed
    {
        return data_get($this->model, $this->dbColumn());
    }

    public function render(): HtmlString
    {
        $value = $this->value();

        if (!empty($view = $this->get(Config::view))) {
            $html = Blade::render(
                $view,
                [
                    'model' => $this->model,
                    'value' => $this->value(),
                    'column' => $this,
                ] + $this->get(Config::view_params)
            );

            return new HtmlString($html);
        }

        if (!empty($formatClosure = $this->get(Config::format_closure))) {
            $value = $formatClosure($value, $this->model, $this);
        }

        return new HtmlString($value);
    }

    public function isRelation(): bool
    {
        return Str::of($this->dbColumn())->contains('.');
    }

    public function getRelationNesting(): int
    {
        return count(Str::of($this->getRelation())->explode('.'));
    }

    public function getRelation(): string
    {
        if (!$this->isRelation()) {
            return "";
        }

        return Str::of($this->dbColumn())->beforeLast('.');
    }

    public function getField(): string
    {
        return Str::of($this->dbColumn())->afterLast('.');
    }

    public function hidden(Closure|bool $when = true): static
    {
        return $this->set(Config::hidden, $when);
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

        return true;
    }

    public function view(string $name, array $params = []): static
    {
        return $this->set(Config::view, $name)
            ->set(Config::view_params, $params);
    }

    public function withFilter(string $filterName)
    {
        //TODO
    }
}
