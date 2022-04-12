<?php

namespace DefStudio\WiredTables;

use DefStudio\WiredTables\Concerns\BuildsQuery;
use DefStudio\WiredTables\Concerns\HasColumns;
use DefStudio\WiredTables\Concerns\HasConfiguration;
use DefStudio\WiredTables\Concerns\HasPagination;
use DefStudio\WiredTables\Concerns\HasSorting;
use DefStudio\WiredTables\Concerns\HasViews;
use Illuminate\Contracts\View\View;
use Livewire\Component;

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
