<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\ColumnException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\HtmlString;

trait HasColumns
{
    /** @var Column[] */
    private array $_columns;
    private bool $_columnsLocked = true;

    public function bootedHasColumns(): void
    {
        $this->_columns = [];

        $this->_columnsLocked = false;
        $this->columns();
        $this->_columnsLocked = true;

        if (empty($this->_columns)) {
            throw ColumnException::noColumnDefined(static::class);
        }
    }

    abstract protected function columns(): void;

    /**
     * @return Column[]
     */
    public function getColumnsProperty(): array
    {
        return $this->_columns;
    }

    protected function column(string $name, ?string $dbColumn = null): Column
    {
        if ($this->_columnsLocked) {
            throw ColumnException::locked();
        }

        if ($this->getColumn($name) !== null) {
            throw ColumnException::duplicatedColumn($name);
        }

        $column = new Column($this, $name, $dbColumn);

        $processedColumn = $this->registeringColumn($column);

        if ($processedColumn === false) {
            return $column;
        }

        $this->_columns[] = $processedColumn;

        return $processedColumn;
    }

    protected function registeringColumn(Column $column): false|Column
    {
        return $column;
    }

    public function getColumn(string $name): Column|null
    {
        return collect($this->_columns)->first(fn (Column $column) => $column->name() === $name);
    }

    protected function applyEagerLoading(Builder|Relation $query): void
    {
        $relations = [];
        foreach ($this->_columns as $column) {
            if ($column->isRelation()) {
                $relations[] = $column->getRelation();
            }
        }

        $relations = array_filter($relations);

        $query->with($relations);
    }

    public function getColumnSum(string $name): HtmlString
    {
        if (empty($name)) {
            return new HtmlString();
        }

        $column = $this->getColumn($name);

        $with_sum = $column->get(Config::with_sum);

        if (!$with_sum) {
            return new HtmlString();
        }

        if ($format = $column->get(Config::sum_format)) {
            $visible_rows_sum = $with_sum === true
                ? $this->rows->sum(fn ($model) => $column->setModel($model)->value())
                : $this->rows->sum(fn ($model) => $column->setModel($model)->runClosure($with_sum));

            $filtered_rows_sum = $with_sum === true
                ? $this->filteredRows->sum(fn ($model) => $column->setModel($model)->value())
                : $this->filteredRows->sum(fn ($model) => $column->setModel($model)->runClosure($with_sum));

            return $format($visible_rows_sum, $filtered_rows_sum);
        } else {
            $rows = $column->get(Config::sum_target) === Config::sum_target_visible
                ? $this->rows
                : $this->filteredRows;


            $sum = $with_sum === true
                ? $rows->sum(fn ($model) => $column->setModel($model)->value())
                : $rows->sum(fn ($model) => $column->setModel($model)->runClosure($with_sum));

            return new HtmlString($sum);
        }
    }
}
