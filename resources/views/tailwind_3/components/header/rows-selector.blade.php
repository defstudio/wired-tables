<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */

?>

@if($this->shouldShowRowsSelector())
    <th scope="col"
        wire:key="wt-{{$this->id}}-select-all-header"
        {{$attributes->class([
                   "px-6 text-left align-middle",
                   "py-3" => !$this->shouldShowColumnFilters(),
                   "pt-3" => $this->shouldShowColumnFilters(),
                ])}}
    >
        <x-wired-tables::elements.checkbox wire:key="wt-{{$this->id}}-select-all" wire:model="allSelected"/>
        @if($this->allSelected && $this->rows->hasPages())
            <button role="button" class="text-gray-500 font-normal text-xs hover:text-gray-700">Select All</button>
        @endif
    </th>
@endif
