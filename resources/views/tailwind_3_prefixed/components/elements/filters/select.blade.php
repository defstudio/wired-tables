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

    <select id="wt-{{$this->id}}-filter-{{$filter->key()}}"
            name="filterValues[{{$filter->key()}}]"
            wire:model.debounce="filterValues.{{$filter->key()}}"
            class="block  border-gray-300 focus:border-indigo-300
                  focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                  rounded-md shadow-sm px-2 py-1 pr-8 w-full min-w-[190px] cursor-pointer"
    >
        <option value="">{{$filter->get(DefStudio\WiredTables\Enums\Config::hint)}}</option>
        @foreach($filter->get(DefStudio\WiredTables\Enums\Config::options) as $value => $label)
            <option value="{{$value}}">{{$label}}</option>
        @endforeach
    </select>
</div>

