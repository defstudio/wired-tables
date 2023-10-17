<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Filter;
use DefStudio\WiredTables\Exceptions\FilterException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * @mixin WiredTable
 */
trait HasFilters
{
    /** @var Filter[] */
    private array $_filters = [];

    /** @var array */
    public array $filterValues = [];

    private bool $_filtersLocked = true;

    public function bootedHasFilters(): void
    {
        if (empty($this->_filters)) {
            $this->_filtersLocked = false;
            $this->filters();
            $this->_filtersLocked = true;
        }

        if (empty($this->filterValues)) {
            $this->filterValues = $this->getState('filters', []);
        } else {
            $this->storeState('filters', $this->filterValues);
        }

        foreach ($this->_filters as $filter) {
            if (!isset($this->filterValues[$filter->key()])) {
                $this->filterValues[$filter->key()] = null;
            }
        }
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

    public function filter(string $name, string $key = null): Filter
    {
        if ($this->_filtersLocked) {
            throw FilterException::locked();
        }

        $filter = new Filter($this, $name, $key);

        if ($this->getFilter($filter->key()) !== null) {
            throw FilterException::duplicated($filter->name());
        }

        $this->_filters[] = $filter;

        return $filter;
    }

    public function getFilter(string $key): Filter|null
    {
        return collect($this->_filters)->first(fn (Filter $filter) => $filter->key() === $key);
    }

    public function getFilterByName(string $name): Filter|null
    {
        return collect($this->_filters)->first(fn (Filter $filter) => $filter->name() === $name);
    }

    public function updatedFilterValues($value, $key): void
    {
        if ($this->paginationEnabled()) {
            $this->setPage(1);
        }

        collect($this->_filters)
            ->filter(fn (Filter $filter) => $filter->type() === Filter::TYPE_CHECKBOX)
            ->reject(fn (Filter $filter) => $this->filterValues[$filter->key()])
            ->each(fn (Filter $filter) => $this->filterValues[$filter->key()] = null);

        $this->storeState('filters', $this->filterValues);
    }

    public function hasFilters(): bool
    {
        return count($this->_filters) > 0;
    }

    public function shouldShowFiltersSelector(): bool
    {
        return $this->globalFilters()
            ->some(fn (Filter $filter) => $filter->isVisible());
    }

    public function shouldShowColumnFilters(): bool
    {
        return $this->columnFilters()->some(fn ($filter) => $filter->isVisible());
    }

    public function activeFilters(): Collection
    {
        return collect($this->_filters)
            ->each(function (Filter $filter) {
                if (!$filter->isVisible()) {
                    $this->clearFilter($filter->key());
                }
            })
            ->filter(fn (Filter $filter) => $filter->isActive())
            ->keyBy(fn (Filter $filter) => $filter->key());
    }

    public function globalFilters(): Collection
    {
        return collect($this->_filters)
            ->reject(fn ($filter) => $filter->isColumnFilter());
    }

    public function columnFilters(): Collection
    {
        return collect($this->_filters)
            ->filter(fn ($filter) => $filter->isColumnFilter());
    }

    public function clearFilter(string $key): void
    {
        $this->filterValues[$key] = null;
        $this->storeState('filters', $this->filterValues);
    }

    public function applyFilters(Builder|Relation $query): void
    {
        $this->activeFilters()
            ->each(fn (Filter $filter) => $filter->apply($query));
    }

    public function getFilterValue(string $key, string|int|bool $default = null): string|int|bool|null
    {
        if (!$this->_filtersLocked && request()->input('updates.0.payload.name') === "filterValues.$key") {
            return request()->input('updates.0.payload.value', 0);
        }

        return $this->filterValues[$key] ?? $default;
    }
}
