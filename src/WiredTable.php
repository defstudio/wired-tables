<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace DefStudio\WiredTables;

use DefStudio\WiredTables\Concerns\BuildsQuery;
use DefStudio\WiredTables\Concerns\DumpsValues;
use DefStudio\WiredTables\Concerns\HasActions;
use DefStudio\WiredTables\Concerns\HasColumns;
use DefStudio\WiredTables\Concerns\HasConfiguration;
use DefStudio\WiredTables\Concerns\HasFilters;
use DefStudio\WiredTables\Concerns\HasPagination;
use DefStudio\WiredTables\Concerns\HasQueryStrings;
use DefStudio\WiredTables\Concerns\HasSearch;
use DefStudio\WiredTables\Concerns\HasSorting;
use DefStudio\WiredTables\Concerns\PreservesState;
use DefStudio\WiredTables\Concerns\SelectsRows;
use DefStudio\WiredTables\Elements\Action;
use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Elements\Filter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

/**
 * @property-read Collection|LengthAwarePaginator $rows
 * @property-read Collection $filteredRows
 * @property-read int $totalRowsCount
 * @property-read Collection $selectedRows
 * @property-read string $slug
 * @property-read Column[] $columns
 * @property-read Action[] $actions
 * @property-read Filter[] $filters
 */
abstract class WiredTable extends Component
{
    use PreservesState;
    use HasConfiguration;
    use HasQueryStrings;
    use HasColumns;
    use HasSorting;
    use BuildsQuery;
    use HasPagination;
    use HasSearch;
    use HasActions;
    use SelectsRows;
    use DumpsValues;
    use HasFilters;

    /** @internal */
    public string $_cachedSlug;

    /**
     * @internal
     */
    public function getSlugProperty(): string
    {
        return $this->_cachedSlug ??= $this->computeSlug();
    }

    protected function computeSlug(): string
    {
        return '';
    }

    public function render(): View
    {
        return view('wired-tables::main');
    }
}
