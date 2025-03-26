<?php

/** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Elements;

use Carbon\Carbon;
use Closure;
use DefStudio\WiredTables\Configurations\Configuration;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\ColumnException;
use DefStudio\WiredTables\Exceptions\FilterException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class Filter extends Configuration
{
    final public const TYPE_TEXT = 'text';
    final public const TYPE_SELECT = 'select';
    final public const TYPE_MULTISELECT = 'multiselect';
    final public const TYPE_DATE = 'date';
    final public const TYPE_CHECKBOX = 'checkbox';

    private WiredTable $table;

    public function __construct(WiredTable $table, string $name, ?string $key = null)
    {
        $this->table = $table;

        $this->initDefaults();

        $this->set(Config::name, $name)
            ->set(Config::key, $key);
    }

    private function initDefaults()
    {
        $this->set(Config::type, self::TYPE_TEXT);
    }

    public function name(): string
    {
        return $this->get(Config::name);
    }

    public function key(): string
    {
        return $this->get(Config::key, Str::snake($this->get(Config::name)));
    }

    public function placeholder(string $text): static
    {
        return $this->set(Config::placeholder, $text);
    }

    public function select(iterable $options): static
    {
        return $this->set(Config::type, self::TYPE_SELECT)
            ->set(Config::options, $options)
            ->set(Config::placeholder, $this->get(Config::placeholder, '-- select --'));
    }

    public function date(): static
    {
        return $this->set(Config::type, self::TYPE_DATE);
    }

    public function checkbox(): static
    {
        return $this->set(Config::type, self::TYPE_CHECKBOX);
    }

    public function hidden(Closure|bool $when = true): static
    {
        return $this->set(Config::hidden, $when);
    }

    public function toArray(): array
    {
        $config = $this->config;

        $config['key'] = $this->key();
        $config['active'] = $this->isActive();
        $config['value'] = $this->value();

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

        return true;
    }

    public function isActive(): bool
    {
        return filled($this->value());
    }

    public function displayOnColumn(): static
    {
        if ($this->table->getColumn($this->name()) === null) {
            throw ColumnException::notFound($this->name());
        }

        return $this->set(Config::display_on_column, true);
    }

    public function isColumnFilter(): bool
    {
        return !empty($this->get(Config::display_on_column));
    }

    public function type(): string
    {
        return $this->get(Config::type);
    }

    public function value(): mixed
    {
        return $this->table->filterValues[$this->key()] ?? null;
    }

    public function formattedValue(): string
    {
        return match ($this->type()) {
            self::TYPE_SELECT => $this->get(Config::options)[$this->value()] ?? '',
            self::TYPE_DATE => Carbon::make($this->value())?->format('d/m/Y'),
            default => $this->value(),
        };
    }

    public function handle(Closure $handler): static
    {
        return $this->set(Config::handler, $handler);
    }

    public function apply(Builder|Relation $query): void
    {
        $handler = $this->get(Config::handler);
        if (is_callable($this->get(Config::handler))) {
            $handler($query, $this->value());

            return;
        }

        match ($this->type()) {
            self::TYPE_TEXT => $this->applyTextFilter($query),
            default => throw FilterException::noHandlerSet($this->name()),
        };
    }

    public function applyTextFilter(Builder|Relation $query): void
    {
        $column = $this->table->getColumn($this->name());
        if ($column === null) {
            throw FilterException::noHandlerSet($this->name());
        }

        $query->where(
            fn (Builder $searchQuery) => $this->table->applyAutoSearchToColumn($column, $searchQuery, $this->value())
        );
    }
}
