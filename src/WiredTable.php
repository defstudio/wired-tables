<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace DefStudio\WiredTables;

use DefStudio\WiredTables\Concerns\BuildsQuery;
use DefStudio\WiredTables\Concerns\DumpsValues;
use DefStudio\WiredTables\Concerns\HasActions;
use DefStudio\WiredTables\Concerns\HasCache;
use DefStudio\WiredTables\Concerns\HasColumns;
use DefStudio\WiredTables\Concerns\HasConfiguration;
use DefStudio\WiredTables\Concerns\HasFilters;
use DefStudio\WiredTables\Concerns\HasPagination;
use DefStudio\WiredTables\Concerns\HasSearch;
use DefStudio\WiredTables\Concerns\HasSorting;
use DefStudio\WiredTables\Concerns\SelectsRows;
use DefStudio\WiredTables\Elements\Action;
use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Elements\Filter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * @property-read Collection|LengthAwarePaginator $rows
 * @property-read Collection $selectedRows
 * @property-read Column[] $columns
 * @property-read Action[] $actions
 * @property-read Filter[] $filters
 */
abstract class WiredTable extends Component
{
    use HasCache;
    use HasConfiguration;
    use HasColumns;
    use HasSorting;
    use BuildsQuery;
    use HasPagination;
    use HasSearch;
    use HasActions;
    use SelectsRows;
    use DumpsValues;
    use HasFilters;

    public string $tableSlug;

    public $queryString = [
        'search' => ['except' => ''],
        'sorting' => ['except' => [], 'as' => 'sort'],
        'filterValues' => ['except' => '', 'as' => 'filters'],
    ];

    public function mount(): void
    {
        $this->tableSlug = Str::of(URL::current())->slug();
    }

    public function render(): View
    {
        return view('wired-tables::main');
    }
}
