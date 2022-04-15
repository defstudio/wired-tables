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
        {{$attributes->class(['pl-6 py-3 text-left align-middle'])}}
    >
        <x-wired-tables::elements.checkbox wire:key="wt-{{$this->id}}-select-all" wire:model="allSelected"/>
    </th>
@endif
