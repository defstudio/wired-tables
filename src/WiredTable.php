<?php

namespace DefStudio\WiredTables;

use DefStudio\WiredTables\Concerns\BuildsQuery;
use DefStudio\WiredTables\Concerns\HasActions;
use DefStudio\WiredTables\Concerns\HasColumns;
use DefStudio\WiredTables\Concerns\HasConfiguration;
use DefStudio\WiredTables\Concerns\HasPagination;
use DefStudio\WiredTables\Concerns\HasSearch;
use DefStudio\WiredTables\Concerns\HasSorting;
use DefStudio\WiredTables\Elements\Action;
use DefStudio\WiredTables\Elements\Column;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

/**
 * @property-read Collection|LengthAwarePaginator $rows
 * @property-read Column[] $columns
 * @property-read Action[] $actions
 */
abstract class WiredTable extends Component
{
    use HasConfiguration;
    use HasColumns;
    use HasSorting;
    use BuildsQuery;
    use HasPagination;
    use HasSearch;
    use HasActions;

    public $queryString = [
        'sorting' => ['except' => [], 'as' => 'sort'],
    ];

    public function render(): View
    {
        return view('wired-tables::main');
    }
}
