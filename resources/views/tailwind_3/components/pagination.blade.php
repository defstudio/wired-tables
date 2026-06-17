<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

@if($this->paginationEnabled())
    <div {{$attributes->class("sm:flex items-center p-3 pt-0")}}>

        @if($this->pageSize !== 'all')
            <div class="grow">
                {{$this->rows->links('wired-tables::livewire-pagination')}}
            </div>
        @endif

        <div class="grow flex">
            <x-wired-tables::page-size-selector class="ml-auto" wire:key="wt-{{$this->getId()}}-page-size-selector-bottom"/>
        </div>

    </div>
@endif
