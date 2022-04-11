<?php

namespace DefStudio\WiredTables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @template TModel of Illuminate\Database\Eloquent\Model
 */
trait BuildsQuery
{
    /**
     * @var Builder<TModel>|Relation<TModel>
     */
    private Builder|Relation $_query;

    public function bootBuildsQuery(): void
    {
        $this->_query = $this->query();
    }

    /**
     * @return Builder<TModel>|Relation<TModel>
     */
    abstract protected function query(): Builder|Relation;

    /**
     * @return Builder<TModel>|Relation<TModel>
     */
    public function rowsQuery(): Builder|Relation
    {
        $query = $this->query();

        $this->applyEagerLoading($query);
        $this->applySorting($query);


        return $query;
    }

    /**
     * @return Collection<int, TModel>
     */
    public function getRowsProperty(): Collection
    {
        return $this->rowsQuery()->get();
    }
}
