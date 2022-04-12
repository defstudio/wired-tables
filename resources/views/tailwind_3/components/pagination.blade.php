<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $component */
?>

@aware(['component'])

@if($component->paginationEnabled())
    <div class="sm:flex px-3">
        <div class="grow my-3">
            <select id="pageSize-{{$component->id}}" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm pl-2 pr-7 py-2" wire:model="pageSize">
                @foreach($component->config(\DefStudio\WiredTables\Enums\Config::available_page_sizes) as $availPagSize)
                    <option value="{{$availPagSize}}">{{ucfirst($availPagSize)}}</option>
                @endforeach
            </select>
            <label for="pageSize-{{$component->id}}" class="ml-1">
                    results
                      @if($component->pageSize !== 'all')
                    per page
                @endif
                </label>
        </div>
        @if($component->pageSize !== 'all')
            <div class="grow my-3">
                {{$component->rows->links()}}
            </div>
        @endif
    </div>
@endif
