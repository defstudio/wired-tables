<?php

use DefStudio\WiredTables\Elements\Filter;
use Illuminate\View\ComponentAttributeBag;

/** @var Filter $filter */
/** @var ComponentAttributeBag $attributes */
?>

@props(['filter', 'label' => true])

<div wire:key="wt-{{$this->id}}-filter-{{$filter->key()}}-wrapper" {{$attributes}}>
    @if($label)
        <label for="wt-{{$this->id}}-filter-{{$filter->key()}}"
               class="block font-medium text-sm text-gray-500"
        >
            {{$filter->name()}}
        </label>
    @endif

    <input id="wt-{{$this->id}}-filter-{{$filter->key()}}"
           wire:key="wt-{{$this->id}}-filter-{{$filter->key()}}"
           name="filterValues[{{$filter->key()}}]"
           wire:model.debounce="filterValues.{{$filter->key()}}"
           class="block text-indigo-600 mt-2
                  border-gray-300 focus:border-indigo-300
                  focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                  rounded shadow-sm cursor-pointer"
           type="checkbox"
    />
</div>

