<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Enums\Sorting;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Support\Arr;

/**
 * @mixin WiredTable
 */
trait HasSorting
{
    public array $sorting = [];

    public function supportMultipleSorting(): bool
    {
        return $this->configuration()->get(Config::support_multiple_sorting, false);
    }

    public function sort(string $dbColumn): void
    {
        $direction = $this->getSortDirection($dbColumn);
        $direction = $direction->next();

        if (!$this->supportMultipleSorting()){
            $this->sorting = [];
        }

        if($direction === Sorting::none){
            unset($this->sorting[$dbColumn]);
            return;
        }

        $this->sorting[$dbColumn] = $direction->value;
    }

    public function getSortDirection(Column|string $column): Sorting
    {
        $column = is_string($column) ? $column : $column->dbColumn();

        return Sorting::tryFrom($this->sorting[$column] ?? null) ?? Sorting::none;
    }

    public function getSortPosition(Column|string $column): int
    {
        $column = is_string($column) ? $column : $column->dbColumn();

        if(!array_key_exists($column, $this->sorting)){
            return 0;
        }


        return array_search($column, array_keys($this->sorting)) + 1;
    }
}
