<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Exceptions\SearchException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @mixin WiredTable
 */
trait HasSearch
{
    public string $search = '';

    public function isSearchable(): bool
    {
        return collect($this->columns)->some(fn (Column $column) => $column->isSearchable());
    }

    public function applySearch(Builder|Relation $query): void
    {
        if (empty($this->search)) {
            return;
        }

        if (!$this->isSearchable()) {
            return;
        }

        $query->where(function (Builder $searchQuery) {
            foreach ($this->columns as $column) {
                if (!$column->isSearchable()) {
                    continue;
                }

                if ($column->hasSearchClosure()) {
                    $column->applySearchClosure($searchQuery, $this->search);

                    return;
                }

                if ($column->isRelation()) {
                    $model = $searchQuery->getModel();

                    if ($column->getRelationNesting() > 1) {
                        throw SearchException::autosearchNotSupportedForNestedRelations($column->getRelation());
                    }

                    $relation = $column->getRelation();

                    if (!method_exists($model, $relation)) {
                        throw SearchException::relationDoesntExist($relation);
                    }

                    $relation = $model->{$relation}();

                    match ($relation::class) {
                        BelongsTo::class => $this->applySearchToBelongsTo($searchQuery, $column, $relation, $this->search),
                        default => throw SearchException::autosearchRelationNotSupported($model->{$relation}()::class),
                    };

                    return;
                }

                $searchQuery->orWhere($column->getField(), 'like', "%$this->search%");
            }
        });
    }

    protected function applySearchToBelongsTo(Builder|Relation $query, Column $column, BelongsTo $relation, string $term): void
    {
        $query->orWhereRelation($relation->getRelationName(), $column->getField(), 'like', "%$term%");
    }
}
