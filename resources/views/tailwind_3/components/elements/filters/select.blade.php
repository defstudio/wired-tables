<?php

use DefStudio\WiredTables\Elements\Filter;
use Illuminate\View\ComponentAttributeBag;

/** @var Filter $filter */
/** @var ComponentAttributeBag $attributes */
?>

@props(['filter'])

<div wire:key="wt-{{$this->id()}}-filter-{{$filter->key()}}-wrapper" {{$attributes}}>
    @if($this->config(\DefStudio\WiredTables\Enums\Config::group_filters, false))
        <label for="wt-{{$this->id()}}-filter-{{$filter->key()}}"
               class="block font-medium text-sm text-gray-500"
        >
            {{$filter->name()}}
        </label>


        <select id="wt-{{$this->id()}}-filter-{{$filter->key()}}"
                name="filterValues[{{$filter->key()}}]"
                wire:model.live.debounce="filterValues.{{$filter->key()}}"
                class="bg-transparent border focus-visible:outline-0 border-solid
                   block  border-gray-300 focus:border-indigo-300
                   focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                   rounded-md shadow-sm px-2 py-2 pr-8 w-full min-w-[190px] cursor-pointer"
        >
            <option value="">{{$filter->get(DefStudio\WiredTables\Enums\Config::placeholder)}}</option>
            @foreach($filter->get(DefStudio\WiredTables\Enums\Config::options) as $value => $label)
                <option value="{{$value}}">{{$label}}</option>
            @endforeach
        </select>
    @else
        <div class="border border-solid pl-2
                   block  border-gray-300 focus:border-indigo-300
                   focus-within:ring focus-within:ring-indigo-200
                   focus-within:ring-opacity-50
                   rounded-md shadow-sm flex items-center h-10">

            <div>{{$filter->name()}}:</div>

            <select id="wt-{{$this->id()}}-filter-{{$filter->key()}}"
                    name="filterValues[{{$filter->key()}}]"
                    wire:model.live.debounce="filterValues.{{$filter->key()}}"
                    class="border-none bg-transparent focus-visible:outline-0 focus:ring
                     focus:ring-indigo-200 focus:ring-opacity-0 px-2 py-2 pr-8
                     w-full min-w-[190px] cursor-pointer"
            >
                <option value="">{{$filter->get(DefStudio\WiredTables\Enums\Config::placeholder)}}</option>
                @foreach($filter->get(DefStudio\WiredTables\Enums\Config::options) as $value => $label)
                    <option value="{{$value}}">{{$label}}</option>
                @endforeach
            </select>
        </div>
    @endif
</div>

