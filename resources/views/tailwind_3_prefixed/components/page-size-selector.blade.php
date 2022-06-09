<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>


<select {{$attributes->class("tw-px-2 tw-pr-7 tw-py-2 tw-border-gray-300 tw-focus:border-indigo-300 tw-focus:ring tw-focus:ring-indigo-200 tw-focus:ring-opacity-50 tw-rounded-md tw-shadow-sm tw-text-sm tw-text-gray-700 tw-cursor-pointer")}} wire:model="pageSize">
    @foreach($this->config(\DefStudio\WiredTables\Enums\Config::available_page_sizes) as $availPagSize)
        <option value="{{$availPagSize}}">{{ucfirst($availPagSize)}}</option>
    @endforeach
</select>

