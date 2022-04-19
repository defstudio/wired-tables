<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Filter;
use DefStudio\WiredTables\WiredTable;

/**
 * @mixin WiredTable
 */
trait HasFilters
{
    /** @var Filter[] */
    private array $_filters = [];

    private bool $_filtersLocked = true;

    public function bootHasFilters(): void
    {
        $this->_filtersLocked = false;
        $this->filters();
        $this->_filtersLocked = true;
    }

    protected function filters(): void
    {
        // no filters by default
    }

    /**
     * @return Filter[]
     */
    public function getFiltersProperty(): array
    {
        return $this->_filters;
    }

}
