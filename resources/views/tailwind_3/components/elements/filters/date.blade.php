<?php

use DefStudio\WiredTables\Elements\Filter;
use Illuminate\View\ComponentAttributeBag;

/** @var Filter $filter */
/** @var ComponentAttributeBag $attributes */
?>

@props(['filter', 'label' => true])

<div wire:key="wt-{{$this->getId()}}-filter-{{$filter->key()}}-wrapper" {{$attributes}}>
    @if($label)
        <label for="wt-{{$this->getId()}}-filter-{{$filter->key()}}"
               class="block font-medium text-sm text-gray-500"
        >
            {{$filter->name()}}
        </label>
    @endif
    <input id="wt-{{$this->getId()}}-filter-{{$filter->key()}}"
           wire:key="wt-{{$this->getId()}}-filter-{{$filter->key()}}"
           name="filterValues[{{$filter->key()}}]"
           wire:model.debounce="filterValues.{{$filter->key()}}"
           class="bg-transparent border focus-visible:outline-0 border-solid
                  block  border-gray-300 focus:border-indigo-300
                  focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                  rounded-md shadow-sm px-2 py-2 w-full min-w-[190px]"
           type="date"
    />
</div>

