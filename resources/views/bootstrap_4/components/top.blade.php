<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>


<div class="p-3 pb-0">
    <x-wired-tables::debug wire:key="wt-{{$this->id}}-debug"/>

    <div wire:key="wt-{{$this->id}}-info" class="d-flex">
        @if($this->activeFilters()->isNotEmpty())
            <div class="pb-3 d-flex">
                <div class="font-weight-normal py-2 text-muted" style="font-size: 0.75rem; line-height: 1rem;">Filters:</div>
                riprendere da qui
                @foreach($this->activeFilters() as $filter)
                    <div wire:key="wt-{{$this->id}}-filter-{{$filter->key()}}-pill"
                         class="font-medium ml-1 px-1 py-0.5 text-xs text-indigo-600 bg-indigo-200 hover:bg-indigo-300  focus:bg-indigo-100  rounded-md flex cursor-pointer"
                         wire:click="clearFilter('{{$filter->key()}}')"
                    >
                        {{$filter->name()}}: {{$filter->formattedValue()}}

                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($this->sorting))
            <div class="pb-3 ml-auto flex">
                <div class="font-medium py-0.5 text-xs text-gray-500">Sort:</div>
                @foreach($this->sorting as $columnName => $dir)
                    <div wire:key="wt-{{$this->id}}-sort-{{$columnName}}-pill"
                         class="font-medium ml-1 px-1 py-0.5 text-xs text-indigo-600 bg-indigo-200 hover:bg-indigo-300  focus:bg-indigo-100  rounded-md flex cursor-pointer"
                         wire:click="clearSorting('{{$columnName}}')"
                    >
                        {{$columnName}}: {{$dir}}

                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="flex items-center">
        <x-wired-tables::search/>
        <x-wired-tables::filters class="ml-1"/>

        <div class="ml-auto flex">
            <x-wired-tables::actions class="mr-1"/>
            <x-wired-tables::page-size-selector wire:key="wt-{{$this->id}}-page-size-selector-top"/>
        </div>
    </div>
</div>

