<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Exceptions\ColumnException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

trait HasColumns
{
    /** @var Column[] */
    private array $_columns;
    private bool $_columnsLocked = true;

    public function bootHasColumns(): void
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

    protected function column(string $name, string $dbColumn = null): Column
    {
        if ($this->_columnsLocked) {
            throw ColumnException::locked();
        }

        $this->_columns[] = $column = new Column($this, $name, $dbColumn);

        return $column;
    }

    protected function getColumnFromDb(string $dbColumn): Column|null
    {
        foreach ($this->_columns as $column) {
            if ($column->dbColumn() === $dbColumn) {
                return $column;
            }
        }

        return null;
    }

    protected function applyEagerLoading(Builder|Relation $query): void
    {
        $relations = [];
        foreach ($this->_columns as $column) {
            if ($column->isRelationship()) {
                $relations[] = $column->getRelationship();
            }
        }
        $relations = array_filter($relations);

        $query->with($relations);
    }
}
