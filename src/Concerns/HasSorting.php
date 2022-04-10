<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Enums\Sorting;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Support\Arr;

/**
 * @mixin WiredTable
 */
trait HasSorting
{
    private array $sorting = [];

    public function supportMultipleSorting(): bool
    {
        return $this->configuration()->get(Config::support_multiple_sorting, false);
    }

    public function sort(string $dbColumn): void
    {
        $direction = Sorting::tryFrom($this->sorting[$dbColumn]) ?? Sorting::none;
        $direction = $direction->next();

        if ($this->supportMultipleSorting()) {
            $this->sorting = Arr::except($this->sorting, $dbColumn);
        } else {
            $this->sorting = [];
        }

        $this->sorting = Arr::prepend($this->sorting, $direction->value, $dbColumn);
    }
}
