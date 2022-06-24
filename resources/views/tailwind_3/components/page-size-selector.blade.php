<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>


<select {{$attributes->class("bg-transparent border focus-visible:outline-0 border-solid px-2 pr-7 py-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm text-gray-700 cursor-pointer")}} wire:model="pageSize">
    @foreach($this->config(\DefStudio\WiredTables\Enums\Config::available_page_sizes) as $availPagSize)
        <option value="{{$availPagSize}}">{{ucfirst($availPagSize)}}</option>
    @endforeach
</select>

