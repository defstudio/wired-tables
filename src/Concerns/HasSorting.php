<?php

/** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Enums\Sorting;
use DefStudio\WiredTables\Exceptions\SortingException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

/**
 * @mixin WiredTable
 */
trait HasSorting
{
    public array $sorting = [];

    public function bootedHasSorting(): void
    {
        if (empty($this->sorting)) {
            $this->sorting = $this->getState('sorting', []);
        } else {
            $this->storeState('sorting', $this->sorting);
        }
    }

    public function supportMultipleSorting(): bool
    {
        return $this->configuration()->get(Config::support_multiple_sorting, false);
    }

    public function sort(string $columnName): void
    {
        $column = $this->getColumn($columnName);

        if ($column === null) {
            throw SortingException::columnNotFound($columnName);
        }

        if (!$column->isSortable()) {
            throw SortingException::columnNotSortable($column->name());
        }

        $direction = $this->getSortDirection($column->name())->next();

        if (!$this->supportMultipleSorting()) {
            $this->sorting = [];
        }

        if ($direction === Sorting::none) {
            unset($this->sorting[$column->name()]);
            $this->storeState('sorting', null);

            return;
        }

        $this->sorting[$column->name()] = $direction->value;

        $this->storeState('sorting', $this->sorting);
    }

    public function clearSorting(string $columnName = null): bool
    {
        if ($columnName) {
            unset($this->sorting[$columnName]);
        } else {
            $this->sorting = [];
        }

        return $this->storeState('sorting', $this->sorting);
    }

    public function getSortDirection(Column|string $column): Sorting
    {
        $column = is_string($column) ? $column : $column->name();

        if (empty($this->sorting[$column])) {
            return Sorting::none;
        }

        return Sorting::tryFrom($this->sorting[$column]);
    }

    public function getSortPosition(Column|string $column): int
    {
        $column = is_string($column) ? $column : $column->name();

        if (!array_key_exists($column, $this->sorting)) {
            return 0;
        }


        return array_search($column, array_keys($this->sorting)) + 1;
    }

    protected function applySorting(Builder|Relation $query): void
    {
        $sorting = empty($this->sorting)
            ? $this->config(Config::default_sorting, [])
            : $this->sorting;

        foreach ($sorting as $columnName => $dir) {
            $dir = Sorting::from($dir);

            $column = $this->getColumn($columnName);

            if (empty($column)) {
                $this->clearSorting($columnName);
                return;
            }

            if (!$column->isSortable()) {
                $this->clearSorting($columnName);
                throw SortingException::columnNotSortable($column->name());
            }

            if ($column->hasSortClosure()) {
                $column->applySortClosure($query, $dir);
                return;
            }

            if ($column->isRelation()) {
                $model = $query->getModel();

                if ($column->getRelationNesting() > 1) {
                    $this->clearSorting($columnName);
                    throw SortingException::autosortNotSupportedForNestedRelations($column->getRelation());
                }

                $relation = $column->getRelation();

                if (!method_exists($model, $relation)) {
                    $this->clearSorting($columnName);
                    throw SortingException::relationDoesntExist($relation);
                }

                $relation = $model->{$relation}();
                match ($relation::class) {
                    BelongsTo::class => $this->applySortingToBelongsTo($query, $column, $relation, $dir),
                    MorphTo::class => $this->applySortingToMorphTo($query, $column, $relation, $dir),
                    default => $this->clearSorting($columnName) && throw SortingException::autosortRelationNotSupported($relation::class),
                };

                return;
            }

            $query->orderBy($column->getField(), $dir->value);
        }
    }

    protected function applySortingToMorphTo(Builder|Relation $query, Column $column, MorphTo $relation, Sorting $dir): void
    {
        $modelTable = $relation->getModel()->getTable();
        $types = $relation->getModel()->newModelQuery()->distinct()->pluck($relation->getMorphType())->filter();


        if ($types->isEmpty()) {
            return;
        }

        /** @var Model $morphModel */
        $morphModel = app($types->pop());
        $morphClass = $morphModel->getMorphClass();
        $relatedTable = $morphModel->getTable();


        $morphUnionQuery = DB::table($relatedTable)
            ->select($column->getField())
            ->where("$modelTable.{$relation->getMorphType()}", "=", $morphClass)
            ->whereColumn('id', $relation->getQualifiedForeignKeyName());

        $types->each(function (string $type) use ($morphUnionQuery, $column, $relation, $modelTable) {
            /** @var Model $morphModel */
            $morphModel = app($type);
            $morphClass = $morphModel->getMorphClass();

            $relatedTable = $morphModel->getTable();
            $morphUnionQuery->union(
                DB::table($relatedTable)
                    ->select($column->getField())
                    ->where("$modelTable.{$relation->getMorphType()}", "=", $morphClass)
                    ->whereColumn('id', $relation->getQualifiedForeignKeyName())
            );
        });

        $query->orderBy(
            $morphUnionQuery,
            $dir->value,
        );
    }

    protected function applySortingToBelongsTo(Builder|Relation $query, Column $column, BelongsTo $relation, Sorting $dir): void
    {
        $relatedModel = $relation->getModel();
        $relatedTable = $relatedModel->getTable();
        $foreignKey = $relation->getQualifiedForeignKeyName();

        $query->orderBy(
            DB::table($relatedTable)->select($column->getField())->whereColumn($foreignKey, "$relatedTable.id"),
            $dir->value,
        );
    }
}
