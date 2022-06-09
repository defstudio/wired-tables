<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>


@if($this->isSearchable())
    <div class="sm:flex" x-data=>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-gray-400 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <input wire:key="wt-{{$this->id}}-search"
                   id="wt-{{$this->id}}-search"
                   type="text"
                   placeholder="search..."
                   class="border focus-visible:outline-0 border-solid border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm placeholder:text-slate-400 text-gray-800 px-7 py-2"
                   wire:model.debounce="search"
            />

            <div x-show="$wire.search" @click="$wire.set('search', '')" class="absolute flex items-center top-0 bottom-0 right-0 pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-slate-400 cursor-pointer h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>
    </div>
@endif
