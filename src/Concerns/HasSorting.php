<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Enums\Sorting;
use DefStudio\WiredTables\Exceptions\SortingException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        if (! $this->supportMultipleSorting()) {
            $this->sorting = [];
        }

        if ($direction === Sorting::none) {
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

        if (! array_key_exists($column, $this->sorting)) {
            return 0;
        }


        return array_search($column, array_keys($this->sorting)) + 1;
    }

    protected function applySorting(Builder|Relation $query): void
    {
        foreach ($this->sorting as $dbColumn => $dir) {
            $column = $this->getColumnFromDb($dbColumn);

            if (empty($column)) {
                return;
            }

            if (($sortClosure = $column->get(Config::sort_closure))) {
                $sortClosure($query, $dir);

                return;
            }

            if ($column->isRelationship()) {
                $model = $query->getModel();

                $relationships = Str::of($column->getRelationship())->explode('.');

                if (count($relationships) > 1) {
                    throw SortingException::autosortNotSupportedForNestedRelations($column->getRelationship());
                }

                $relationship = $relationships[0];

                if (! method_exists($model, $relationship)) {
                    throw SortingException::relationDoesntExist($relationship);
                }

                $relation = $model->{$relationship}();
                match ($relation::class) {
                    BelongsTo::class => $this->applySortingToBelongsTo($query, $column, $relation, $dir),
                    default => throw SortingException::autosortRelationNotSupported($model->{$relationship}()::class),
                };
            }

            $query->orderBy($column->getField(), $dir);
        }
    }

    protected function applySortingToBelongsTo(Builder|Relation $query, Column $column, BelongsTo $relation, string $dir): void
    {
        $relatedModel = $relation->getModel();
        $relatedTable = $relatedModel->getTable();
        $foreignKey = $relation->getQualifiedForeignKeyName();

        $query->orderBy(
            DB::table($relatedTable)->select($column->getField())->whereColumn($foreignKey, "$relatedTable.id"),
            $dir,
        );
    }
}
