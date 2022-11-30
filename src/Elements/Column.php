<?php

/** @noinspection PhpUnused */

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace DefStudio\WiredTables\Elements;

use Closure;
use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Configurations\Configuration;
use DefStudio\WiredTables\Enums\ColumnType;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Enums\Sorting;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;
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

    protected function runClosure(Closure $formatClosure): mixed
    {
        $reflection = new \ReflectionFunction($formatClosure);

        if ($reflection->getNumberOfParameters() === 0) {
            return $formatClosure($this->value(), $this->model(), $this);
        }


        $firstParameter = $reflection->getParameters()[0];
        $firstParameterType = $firstParameter->getType();

        if ($firstParameterType === null) {
            return $formatClosure($this->value(), $this->model(), $this);
        }

        if ($firstParameterType::class !== \ReflectionNamedType::class) {
            return $formatClosure($this->value(), $this->model(), $this);
        }

        if (!is_subclass_of($firstParameterType->getName(), Model::class)) {
            return $formatClosure($this->value(), $this->model(), $this);
        }

        return $formatClosure($this->model(), $this);
    }

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function model(): Model|null
    {
        if (!isset($this->model)) {
            return null;
        }

        return $this->model;
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

    public function wrapText(bool $enable = true): static
    {
        return $this->set(Config::wrapText, $enable);
    }

    /**
     * @param null|Closure(Builder $query, Sorting $dir): void $sortClosure
     */
    public function sortable(Closure $sortClosure = null): static
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

    /**
     * @param (Closure(mixed $value, Model $model, Column $column): (string|null))|(Closure(Model $model, Column $column): (string|null)) $urlClosure
     */
    public function url(Closure $urlClosure, string $target = null): static
    {
        return $this->set(Config::url, $urlClosure)
            ->set(Config::url_target, $target);
    }

    /**
     * @param (Closure(mixed $value, Model $model, Column $column): (string|array|null))|(Closure(Model $model, Column $column): (string|array|null)) $emitClosure
     */
    public function emit(Closure $emitClosure, string $target = null): static
    {
        return $this->set(Config::emit, $emitClosure);
    }

    public function carbon(string $format): static
    {
        return $this->set(Config::type, ColumnType::carbon)
            ->set(Config::date_format, $format);
    }

    public function boolean(): static
    {
        return $this->set(Config::type, ColumnType::boolean);
    }

    public function enum(): static
    {
        return $this->set(Config::type, ColumnType::enum);
    }

    public function applySortClosure(Builder|Relation $query, Sorting $dir): void
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
     * @param (Closure(mixed $value, Model $model, Column $column): (string|HtmlString))|(Closure(Model $model, Column $column): (string|HtmlString)) $formatClosure
     *
     * @return static
     */
    public function format(Closure $formatClosure): static
    {
        return $this->set(Config::format_closure, $formatClosure);
    }

    public function date(string $format = null): static
    {
        return $this->format(function ($value) use ($format) {
            if (empty($value)) {
                return null;
            }

            $format ??= config('wired-tables.date_format', 'Y-m-d');

            return (new Carbon($value))->format($format);
        });
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
        return data_get($this->model(), Str::of($this->dbColumn())->replace('->', '.'));
    }

    public function getUrl(): string|null
    {
        $urlClosure = $this->get(Config::url);

        if ($urlClosure === null) {
            return null;
        }

        return $this->runClosure($urlClosure);
    }

    public function getEmit(): array|string|null
    {
        $emitClosure = $this->get(Config::emit);

        if ($emitClosure === null) {
            return null;
        }

        return $this->runClosure($emitClosure);
    }

    public function render(): HtmlString
    {
        if (!empty($view = $this->get(Config::view))) {
            $html = Blade::render(
                $view,
                [
                    'model' => $this->model(),
                    'value' => $this->value(),
                    'column' => $this,
                ] + $this->get(Config::view_params)
            );

            return new HtmlString($html);
        }

        if (!empty($formatClosure = $this->get(Config::format_closure))) {
            return new HtmlString($this->runClosure($formatClosure));
        }

        $value = match ($this->get(Config::type)) {
            ColumnType::carbon => $this->value()->format($this->get(Config::date_format)),
            ColumnType::boolean => Blade::render('wired-tables::values.boolean', ['value' => !!$this->value()]),
            ColumnType::enum => $this->value()->value,
            default => $this->value(),
        };

        return new HtmlString($value);
    }

    public function isRelation(): bool
    {
        return Str::of($this->dbColumn())->before('->')->contains('.');
    }

    public function isJson(): bool
    {
        return Str::of($this->dbColumn())->before('.')->contains('->');
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

    /**
     * @param (Closure(mixed $value, Model $model, Column $column): bool)|(Closure(Model $model, Column $column): bool)|bool $when
     */
    public function hidden(Closure|bool $when = true): static
    {
        return $this->set(Config::hidden, $when);
    }

    public function isVisible(): bool
    {
        if (!empty($hidden = $this->get(Config::hidden))) {
            if (is_callable($hidden)) {
                $hidden = $this->runClosure($hidden);
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
