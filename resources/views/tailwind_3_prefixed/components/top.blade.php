<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

<div class="tw-p-3 pb-0">
    <x-wired-tables::debug wire:key="wt-{{$this->id}}-debug"/>


    <div wire:key="wt-{{$this->id}}-info" class="tw-flex">
        @if($this->activeFilters()->isNotEmpty())
            <div class="tw-pb-3 tw-flex">
                <div class="tw-font-medium tw-py-0.5 tw-text-xs tw-text-gray-500">Filters:</div>
                @foreach($this->activeFilters() as $filter)
                    <div wire:key="wt-{{$this->id}}-filter-{{$filter->key()}}-pill"
                         class="tw-font-medium tw-ml-1 tw-px-1 tw-py-0.5 tw-text-xs tw-text-indigo-600 tw-bg-indigo-200 tw-hover:bg-indigo-300  tw-focus:bg-indigo-100  tw-rounded-md tw-flex tw-cursor-pointer"
                         wire:click="clearFilter('{{$filter->key()}}')"
                    >
                        {{$filter->name()}}: {{$filter->formattedValue()}}

                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-ml-2 tw-h-4 tw-w-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($this->sorting))
            <div class="tw-pb-3 tw-ml-auto tw-flex">
                <div class="tw-font-medium tw-py-0.5 tw-text-xs tw-text-gray-500">Sort:</div>
                @foreach($this->sorting as $columnName => $dir)
                    <div wire:key="wt-{{$this->id}}-sort-{{$columnName}}-pill"
                         class="tw-font-medium tw-ml-1 tw-px-1 tw-py-0.5 tw-text-xs tw-text-indigo-600 tw-bg-indigo-200 tw-hover:bg-indigo-300  tw-focus:bg-indigo-100  tw-rounded-md tw-flex tw-cursor-pointer"
                         wire:click="clearSorting('{{$columnName}}')"
                    >
                        {{$columnName}}: {{$dir}}

                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-ml-2 tw-h-4 tw-w-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

