<?php

namespace DefStudio\WiredTables;

use DefStudio\WiredTables\Concerns\BuildsQuery;
use DefStudio\WiredTables\Concerns\HasColumns;
use DefStudio\WiredTables\Concerns\HasConfiguration;
use DefStudio\WiredTables\Concerns\HasPagination;
use DefStudio\WiredTables\Concerns\HasSorting;
use DefStudio\WiredTables\Elements\Column;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

/**
 * @property-read Collection|LengthAwarePaginator $rows
 * @property-read Column[] $columns
 */
abstract class WiredTable extends Component
{
    use HasConfiguration;
    use HasColumns;
    use HasSorting;
    use BuildsQuery;
    use HasPagination;

    public $queryString = [
        'sorting' => ['except' => [], 'as' => 'sort'],
    ];

    public function render(): View
    {
        return view('wired-tables::main');
    }
}
