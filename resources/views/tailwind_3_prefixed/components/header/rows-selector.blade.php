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
                   "tw-px-6 tw-text-left tw-align-middle",
                   "tw-py-3" => !$this->shouldShowColumnFilters(),
                   "tw-pt-3" => $this->shouldShowColumnFilters(),
                ])}}
    >
        <x-wired-tables::elements.checkbox wire:key="wt-{{$this->id}}-select-all" wire:model="allSelected"/>
        @if($this->allSelected && $this->rows->hasPages())
            <button role="button" class="tw-text-gray-500 tw-font-normal tw-text-xs hover:tw-text-gray-700">Select All</button>
        @endif
    </th>
@endif
