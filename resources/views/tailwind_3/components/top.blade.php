<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $component */
?>

@aware(['component'])


<div class="p-3 pb-0">
    <x-wired-tables::debug wire:key="wt-debug-{{$this->id}}"/>

    <div class="flex items-center">
        <x-wired-tables::search />

        <x-wired-tables::page-size-selector wire:key="wt-page-size-selector-top-{{$this->id}}" class="ml-auto" />
    </div>
</div>
