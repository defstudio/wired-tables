<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

@if($this->shouldShowFiltersSelector())
    <div {{$attributes->class('relative')}} wire:key="wt-{{$this->id}}-filters-wrapper" x-data="{show: false}">
        <button
            wire:key="wt-{{$this->id}}-filters-dropdown"
            {{$attributes->class("flex items-center px-2 py-2 border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm text-gray-700 ")}}
            @click="show = !show"
        >
            Filters


            <span @class([
                    "ml-2 min-w-[20px] bg-indigo-300 rounded-full text-xs  text-indigo-600",
                    "opacity-0" => $this->activeFilters()->reject(fn($filter) => $filter->isColumnFilter())->count() === 0
                  ])
            >{{$this->activeFilters()->reject(fn($filter) => $filter->isColumnFilter())->count()}}</span>

            <svg xmlns="http://www.w3.org/2000/svg" class=" h-5 w-5 bg-[right_0.5rem_center] bg-[length:1.5em_1.5em] bg-no-repeat stroke-[#6b7280]" fill="none" viewBox="0 0 20 20" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width='1.5' d="M6 8l4 4 4-4"/>
            </svg>
        </button>
        <div wire:key="wt-{{$this->id}}-filters-container"
             x-show="show"
             x-on:click.outside="show = false"
             class="absolute table left-0 top-[calc(100%_+_10px)] bg-white z-10 shadow-md border border-gray-300 py-0.5 px-1 rounded text-sm text-gray-700"
             x-cloak
        >
            @php($visibleFilters = $this->globalFilters()->filter(fn($filter) => $filter->isVisible()))
            @foreach($visibleFilters->chunk($this->config(\DefStudio\WiredTables\Enums\Config::filters_columns, $visibleFilters->count() > 2 ? 2 : 1)) as $filter_group)
                <div class="table-row">
                    @foreach($filter_group as $index => $filter)
                        <?php /** @var \DefStudio\WiredTables\Elements\Filter $filter */ ?>
                        <div wire:key="wt-{{$this->id}}-filter-{{$filter->key()}}-container" class="table-cell p-2">
                            <x-dynamic-component class="mt-1"
                                                 component='wired-tables::elements.filters.{{$filter->type()}}'
                                                 :filter="$filter"
                            />
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

@endif
