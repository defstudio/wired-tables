<?php

use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */

?>
@if($this->shouldShowColumnFilters())
    <tr class="-mt-3">
        @if($this->shouldShowRowsSelector())
            <th class="px-6 pb-1 relative"></th>
        @endif
        @foreach($this->columns as $column)
            <th class="px-6 pb-1 relative">
                @php($columnFilter = $this->getFilterByName($column->name()))
                @if($columnFilter && $columnFilter->isColumnFilter())
                    <x-dynamic-component class="w-full"
                                         component='wired-tables::elements.filters.{{$columnFilter->type()}}'
                                         :filter="$columnFilter"
                                         :label="false"
                    />
                @endif
            </th>
        @endforeach
    </tr>
@endif
