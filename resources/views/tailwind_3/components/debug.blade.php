<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\View\ComponentAttributeBag;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */
?>

@if($this->config(\DefStudio\WiredTables\Enums\Config::debug))
    <div {{$attributes->class("my-3 border rounded-md overflow-hidden")}}>
        <div class="flex flex-column" x-data="{section: 'table', column: 0, action: 0}">
            <ul class="min-w-[190px]">
                <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'table'}">
                    <button @click="section = 'table'" role="button">Table Configuration</button>
                </li>
                <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'header'}">
                    <button @click="section = 'header'" role="button">Header Configuration</button>
                </li>
                <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'sorting'}">
                    <button @click="section = 'sorting'" role="button">Sorting</button>
                </li>
                <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'pagination'}">
                    <button @click="section = 'pagination'" role="button">Pagination</button>
                </li>
                <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'columns'}">
                    <button @click="section = 'columns'" role="button">Columns</button>
                </li>
                <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'actions'}">
                    <button @click="section = 'actions'" role="button">Actions</button>
                </li>
                <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'query'}">
                    <button @click="section = 'query'" role="button">Query</button>
                </li>
            </ul>
            <div class="grow rounded-t border-l-2">
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
                <div x-show="section === 'columns'">
                    <ul class="flex flex-wrap text-sm font-medium text-center">
                        @foreach($this->columns as $index => $column)
                            <li class="mr-2">
                                <button class="inline-block p-4 rounded border-t-2"
                                        :class="{'border-t-indigo-500': column ==={{$index}}}"
                                        @click="column = {{$index}}"
                                >
                                    {{$column->name()}}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div>
                        @foreach($this->columns as $index => $column)
                            <div x-show="column === {{$index}}" class="w-full">
                                {{$column->dump()}}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div x-show="section === 'actions'">
                    <ul class="flex flex-wrap text-sm font-medium text-center">
                        @foreach($this->actions as $index => $action)
                            <li class="mr-2">
                                <button class="inline-block p-4 rounded border-t-2"
                                        :class="{'border-t-indigo-500': action ==={{$index}}}"
                                        @click="action = {{$index}}"
                                >
                                    {{$action->name()}}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div>
                        @foreach($this->actions as $index => $action)
                            <div x-show="action === {{$index}}" class="w-full">
                                {{$action->dump()}}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-4" x-show="section === 'query'">
                    {{ $this->debugQuery()}}
                </div>
            </div>
        </div>
    </div>


@endif
