<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Action;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @mixin WiredTable
 */
trait SelectsRows
{

    /** @var array<int, int|string> */
    public array $selection = [];

    public bool $allSelected = false;
    public bool $allPagesSelected = false;

    public function shouldShowRowsSelector(): bool
    {
        return collect($this->_actions)->some(fn (Action $action) => $action->requiresRowsSelection());
    }

    public function shouldShowActionsSelector(): bool
    {
        if (!$this->hasActions()) {
            return false;
        }

        if ($this->config(Config::always_show_actions)) {
            return true;
        }

        if (collect($this->_actions)->some(fn (Action $action) => !$action->requiresRowsSelection())) {
            return true;
        }

        if (!empty($this->selection)) {
            return true;
        }

        return false;
    }

    public function selectedIds(): array
    {
        return collect($this->selection)->keys()->sort()->toArray();
    }

    public function selectVisibleRows(): void
    {
        $this->selection = collect($this->getVisibleRowsIds())->mapWithKeys(fn (int|string $id) => [$id => true])->toArray();
    }

    public function getVisibleRowsIds(): array
    {
        return $this->rows->values()->pluck($this->configuration()->get(Config::id_field))->sort()->toArray();
    }

    public function unselectAllRows(): void
    {
        $this->selection = [];
    }

    public function getRowId(Model $model): int|string
    {
        return data_get($model, $this->configuration()->get(Config::id_field));
    }

    public function updatedAllSelected(bool $selected): void
    {
        if ($selected) {
            $this->selectVisibleRows();
        } else {
            $this->unselectAllRows();
        }

        $this->updatedSelection($selected);
    }

    public function updatedSelection(bool $selected): void
    {
        if (!$selected) {
            $this->allSelected = false;
            $this->allPagesSelected = false;
        }

        $this->selection = collect($this->selection)->filter()->toArray();

        $this->allSelected = collect($this->getVisibleRowsIds())
            ->diff($this->selectedIds())
            ->isEmpty();
    }

    protected function applyRowsSelection(Builder|Relation $query): void
    {
        if(empty($this->selection)){
            return;
        }

        $query->whereIn($this->config(Config::id_field), $this->selectedIds());
    }
}
