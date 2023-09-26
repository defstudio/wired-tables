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
        wire:key="wt-{{$this->id()}}-select-all-header"
        {{$attributes->class([
                   "px-6 text-left align-middle",
                   "py-3" => !$this->shouldShowColumnFilters(),
                   "pt-3" => $this->shouldShowColumnFilters(),
                ])}}
    >
        <x-wired-tables::elements.checkbox wire:key="wt-{{$this->id()}}-select-all" wire:model.live="allSelected"/>
    </th>
@endif
