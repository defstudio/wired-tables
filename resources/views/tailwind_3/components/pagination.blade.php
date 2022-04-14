<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $component */
?>

@aware(['component'])

@if($component->paginationEnabled())
    <div class="sm:flex items-center p-3 pt-0">

        @if($component->pageSize !== 'all')
            <div class="grow">
                {{$component->rows->links()}}
            </div>
        @endif

        <div class="grow flex">
            <x-wired-tables::page-size-selector class="ml-auto" wire:key="wt-page-size-selector-bottom-{{$this->id}}"/>
        </div>

    </div>
@endif
