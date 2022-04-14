<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

<div class="p-3 pb-0">
    <x-wired-tables::debug wire:key="wt-debug-{{$this->id}}"/>

    <div class="flex items-center">
        <x-wired-tables::search/>


        <div class="ml-auto flex">
            <x-wired-tables::actions class="mr-1"/>

            <x-wired-tables::page-size-selector wire:key="wt-page-size-selector-top-{{$this->id}}"/>
        </div>
    </div>
</div>
