<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $component */
?>

@aware(['component'])

@if($component->config(\DefStudio\WiredTables\Enums\Config::debug))
    <div class="m-2 p-2 border rounded-md">
        <strong><u>Debug Info</u></strong>
        <div class="flex flex-column" x-data="{section: 'table', column: 0}">
            <ul>
               <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'table'}"><button @click="section = 'table'" role="button">Table Configuration</button></li>
               <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'header'}"><button @click="section = 'header'" role="button">Header Configuration</button></li>
               <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'sorting'}"><button @click="section = 'sorting'" role="button">Sorting</button></li>
               <li class="py-2 px-4 text-gray-700" :class="{'bg-gray-200': section === 'columns'}"><button @click="section = 'columns'" role="button">Columns</button></li>
            </ul>
            <div class="grow rounded-t border-l-2">
                <div x-show="section === 'table'">@php($component->configuration()->dump())</div>
                <div x-show="section === 'header'">@php($component->configuration()->header->dump())</div>
                <div x-show="section === 'sorting'">@dump($component->sorting)</div>
                <div x-show="section === 'columns'">
                    <ul class="flex flex-wrap text-sm font-medium text-center">
                        @foreach($component->columns as $index => $column)
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
                        @foreach($component->columns as $index => $column)
                            <div x-show="column === {{$index}}" class="w-full">
                                {{$column->dump()}}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


@endif
