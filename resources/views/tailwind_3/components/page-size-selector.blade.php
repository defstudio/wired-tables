<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $component */
?>

@aware(['component'])

<select {{$attributes->class("border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm text-gray-700 pl-2 pr-7 py-2")}} wire:model="pageSize">
    @foreach($component->config(\DefStudio\WiredTables\Enums\Config::available_page_sizes) as $availPagSize)
        <option value="{{$availPagSize}}">{{ucfirst($availPagSize)}}</option>
    @endforeach
</select>

