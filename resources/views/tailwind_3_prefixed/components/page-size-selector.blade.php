<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */

$availablePageSize = $this->config(\DefStudio\WiredTables\Enums\Config::available_page_sizes);

?>

@if($availablePageSize && count($availablePageSize)>1)
    <select {{$attributes->class("tw-bg-transparent tw-border focus-visible:tw-outline-0 tw-border-solid tw-px-2 tw-pr-7 tw-py-2 tw-border-gray-300 focus:tw-border-indigo-300 focus:tw-ring focus:tw-ring-indigo-200 focus:tw-ring-opacity-50 tw-rounded-md tw-shadow-sm tw-text-sm tw-text-gray-700 tw-cursor-pointer")}} wire:model="pageSize">
        @foreach($this->config(\DefStudio\WiredTables\Enums\Config::available_page_sizes) as $availPagSize)
            <option value="{{$availPagSize}}">{{ucfirst($availPagSize)}}</option>
        @endforeach
    </select>
@endif

