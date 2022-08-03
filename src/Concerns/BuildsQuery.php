<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

/** @noinspection PhpDocMissingThrowsInspection */
/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

trait BuildsQuery
{
    private Builder|Relation $_query;

    public function bootedBuildsQuery(): void
    {
        $this->_query = $this->query();
    }

    abstract protected function query(): Builder|Relation;

    public function rows(): Builder|Relation
    {
        $query = $this->_query->clone();

        $this->applyEagerLoading($query);
        $this->applyFilters($query);
        $this->applySearch($query);
        $this->applySorting($query);


        return $query;
    }

    protected function paginatedResults(): Collection|LengthAwarePaginator
    {
        $query = $this->rows()->clone();

        if (!$this->paginationEnabled()) {
            return $query->get();
        }

        if ($this->pageSize === 'all') {
            return $query->get();
        }

        return $query->paginate($this->pageSize);
    }

    public function getTotalRowsCountProperty(): int
    {
        if($this->rows instanceof LengthAwarePaginator){
            return $this->rows->total();
        }

        return $this->rows->count();
    }

    public function getRowsProperty(): Collection|LengthAwarePaginator
    {
        return $this->paginatedResults();
    }

    public function debugQuery(Builder|Relation $query = null): string
    {
        $paginationEnabled = $query === null && $this->paginationEnabled();

        if (!config('app.debug')) {
            return "";
        }

        $query ??= $this->rows()->clone();

        return Str::of($query->toSql())
            ->replaceArray('?', collect($query->getBindings())->map(function ($binding) {
                return is_numeric($binding) ? $binding : "'{$binding}'";
            })->toArray())
            ->when($paginationEnabled, fn (Stringable $str) => $str->append(' limit ', $this->pageSize, ' offset ', $this->pageSize * ($this->page - 1)));
    }

    public function selectedRows(): Builder|Relation
    {
        $query = $this->_query->clone();

        $this->applyEagerLoading($query);
        $this->applyRowsSelection($query);

        return $query;
    }

    public function getSelectedRowsProperty(): Collection
    {
        return $this->selectedRows()->get();
    }
}
