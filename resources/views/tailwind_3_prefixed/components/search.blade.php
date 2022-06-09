<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>


@if($this->isSearchable())
    <div class="sm:tw-flex" x-data=>
        <div class="tw-relative">
            <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-flex tw-items-center tw-pl-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="tw-stroke-gray-400 tw-h-4 tw-w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <input wire:key="wt-{{$this->id}}-search"
                   id="wt-{{$this->id}}-search"
                   type="text"
                   placeholder="search..."
                   class="tw-border focus-visible:tw-outline-0 tw-border-solid tw-border-gray-300  focus:tw-border-indigo-300 focus:tw-ring focus:tw-ring-indigo-200 focus:tw-ring-opacity-50 tw-rounded-md tw-shadow-sm tw-text-sm placeholder:tw-text-slate-400 tw-text-gray-800 tw-px-7 tw-py-2"
                   wire:model.debounce="search"
            />

            <div x-show="$wire.search" @click="$wire.set('search', '')" class="tw-absolute tw-flex tw-items-center tw-top-0 tw-bottom-0 tw-right-0 tw-pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="tw-stroke-slate-400 tw-cursor-pointer tw-h-4 tw-w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>
    </div>
@endif
