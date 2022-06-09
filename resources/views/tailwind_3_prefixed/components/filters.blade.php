<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

@if($this->shouldShowFiltersSelector())
    <div {{$attributes->class('tw-relative')}} wire:key="wt-{{$this->id}}-filters-wrapper" x-data="{show: false}">
        <button
            wire:key="wt-{{$this->id}}-filters-dropdown"
            {{$attributes->class("tw-flex tw-items-center tw-px-2 tw-py-2 tw-border tw-border-gray-300 tw-focus:border-indigo-300 tw-focus:ring tw-focus:ring-indigo-200 tw-focus:ring-opacity-50 tw-rounded-md tw-shadow-sm tw-text-sm tw-text-gray-700 ")}}
            @click="show = !show"
        >
            Filters


            <span @class([
                    "tw-ml-2 tw-min-w-[20px] tw-bg-indigo-300 tw-rounded-full tw-text-xs  tw-text-indigo-600",
                    "tw-opacity-0" => $this->activeFilters()->reject(fn($filter) => $filter->isColumnFilter())->count() === 0
                  ])
            >{{$this->activeFilters()->reject(fn($filter) => $filter->isColumnFilter())->count()}}</span>

            <svg xmlns="http://www.w3.org/2000/svg" class=" tw-h-5 tw-w-5 tw-bg-[right_0.5rem_center] tw-bg-[length:1.5em_1.5em] tw-bg-no-repeat tw-stroke-[#6b7280]" fill="none" viewBox="0 0 20 20" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width='1.5' d="M6 8l4 4 4-4"/>
            </svg>
        </button>
        <div wire:key="wt-{{$this->id}}-filters-container"
             x-show="show"
             x-on:click.outside="show = false"
             class="tw-table tw-absolute tw-left-0 tw-top-[calc(100%_+_10px)] tw-bg-white tw-z-10 tw-shadow-md border tw-border-gray-300 tw-py-0.5 tw-px-1 tw-rounded tw-text-sm tw-text-gray-700"
             x-cloak
        >
            @php($visibleFilters = $this->globalFilters()->filter(fn($filter) => $filter->isVisible()))
            @foreach($visibleFilters->chunk($this->config(\DefStudio\WiredTables\Enums\Config::filters_columns, $visibleFilters->count() > 2 ? 2 : 1)) as $filter_group)
                <div class="tw-table-row">
                    @foreach($filter_group as $index => $filter)
                        <?php /** @var \DefStudio\WiredTables\Elements\Filter $filter */ ?>
                        <div wire:key="wt-{{$this->id}}-filter-{{$filter->key()}}-container" class="table-cell tw-p-2">
                            <x-dynamic-component class="tw-mt-1"
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
