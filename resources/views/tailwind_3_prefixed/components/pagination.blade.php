<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

@if($this->paginationEnabled())
    <div {{$attributes->class("sm:tw-flex tw-items-center tw-p-3 tw-pt-0")}}>

        @if($this->pageSize !== 'all')
            <div class="tw-grow">
                {{$this->rows->links('wired-tables::livewire-pagination')}}
            </div>
        @endif

        <div class="tw-grow tw-flex">
            <x-wired-tables::page-size-selector class="tw-ml-auto" wire:key="wt-{{$this->getId()}}-page-size-selector-bottom"/>
        </div>

    </div>
@endif
