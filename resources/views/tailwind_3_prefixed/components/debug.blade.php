<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\View\ComponentAttributeBag;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */
?>

@if($this->config(\DefStudio\WiredTables\Enums\Config::debug))
    <div {{$attributes->class("tw-my-3 tw-border tw-rounded-md tw-overflow-hidden")}} x-cloak>
        <div class="tw-flex tw-flex-column" x-data="{section: 'table', column: 0, action: 0, filter: 0, 'dumps': 'Misc'}">
            <ul class="min-w-[190px]">
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'table'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'table'" role="button">Table Configuration</button>
                </li>
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'header'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'header'" role="button">Header Configuration</button>
                </li>
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'sorting'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'sorting'" role="button">Sorting</button>
                </li>
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'pagination'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'pagination'" role="button">Pagination</button>
                </li>
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'columns'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'columns'" role="button">Columns</button>
                </li>
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'actions'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'actions'" role="button">Actions</button>
                </li>
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'filters'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'filters'" role="button">Filters</button>
                </li>
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'selection'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'selection'" role="button">Selection</button>
                </li>
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'query'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'query'" role="button">Query</button>
                </li>
                <li class="tw-text-gray-700" :class="{'tw-bg-gray-200': section === 'dumps'}">
                    <button class="tw-py-2 tw-px-4 tw-w-full tw-text-left" @click="section = 'dumps'" role="button">Dumps</button>
                </li>
            </ul>
            <div class="tw-grow tw-rounded-t tw-border-l-2">
                <div x-show="section === 'table'">@php($this->configuration()->dump())</div>
                <div x-show="section === 'header'">@php($this->configuration()->header->dump())</div>
                <div x-show="section === 'sorting'">@dump($this->sorting)</div>
                <div x-show="section === 'pagination'">
                    @if($this->paginationEnabled())
                        @dump(['page' => $this->page, 'page size' => $this->pageSize])
                    @else
                        @dump("Pagination disabled")
                    @endif
                </div>
                <div x-show="section === 'selection'">
                    @if($this->shouldShowRowsSelector())
                        @dump(['selected' => collect($this->selection)->keys()->toArray()])
                    @else
                        @dump("Row selection not needed")
                    @endif
                </div>
                <div x-show="section === 'columns'">
                    <ul class="tw-flex tw-flex-wrap tw-text-sm tw-font-medium tw-text-center">
                        @foreach($this->columns as $index => $column)
                            <li class="tw-mr-2">
                                <button class="tw-inline-block tw-p-4 tw-rounded tw-border-t-2"
                                        :class="{'tw-border-t-indigo-500': column ==={{$index}}}"
                                        @click="column = {{$index}}"
                                >
                                    {{$column->name()}}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div>
                        @foreach($this->columns as $index => $column)
                            <div x-show="column === {{$index}}" class="tw-w-full">
                                {{$column->dump()}}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div x-show="section === 'actions'">
                    <ul class="tw-flex tw-flex-wrap tw-text-sm tw-font-medium tw-text-center">
                        @foreach($this->actions as $index => $action)
                            <li class="tw-mr-2">
                                <button class="tw-inline-block tw-p-4 tw-rounded tw-border-t-2"
                                        :class="{'tw-border-t-indigo-500': action ==={{$index}}}"
                                        @click="action = {{$index}}"
                                >
                                    {{$action->name()}}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div>
                        @foreach($this->actions as $index => $action)
                            <div x-show="action === {{$index}}" class="tw-w-full">
                                {{$action->dump()}}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div x-show="section === 'filters'">
                    <ul class="tw-flex tw-flex-wrap tw-text-sm tw-font-medium tw-text-center">
                        @foreach($this->filters as $index => $filter)
                            <li class="tw-mr-2">
                                <button class="tw-inline-block tw-p-4 tw-rounded tw-border-t-2"
                                        :class="{'tw-border-t-indigo-500': filter ==={{$index}}}"
                                        @click="filter = {{$index}}"
                                >
                                    {{$filter->name()}}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div>
                        @foreach($this->filters as $index => $filter)
                            <div x-show="filter === {{$index}}" class="tw-w-full">
                                {{$filter->dump()}}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tw-p-4" x-show="section === 'query'">
                    {{ $this->debugQuery()}}
                </div>
                <div x-show="section === 'dumps'">
                    <ul class="tw-flex tw-flex-wrap tw-text-sm tw-font-medium tw-text-center">
                        @foreach($this->dumpLabels() as $label)
                            <li class="tw-mr-2">
                                <button class="tw-inline-block tw-p-4 tw-rounded tw-border-t-2"
                                        :class="{'tw-border-t-indigo-500': dumps === '{{$label}}'}"
                                        @click="dumps = '{{$label}}'"
                                >{{$label}}</button>
                            </li>
                        @endforeach
                    </ul>
                    <div>
                        @foreach($this->dumpLabels() as $label)
                            <div x-show="dumps === '{{$label}}'" class="tw-w-full">
                                @foreach($this->dumps()->where(fn(DefStudio\WiredTables\Elements\Dump $dump) => $dump->getLabel() === $label) as $dump)
                                    {{$dump->print()}}
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
