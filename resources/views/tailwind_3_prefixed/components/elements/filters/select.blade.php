<?php

use DefStudio\WiredTables\Elements\Filter;
use Illuminate\View\ComponentAttributeBag;

/** @var Filter $filter */
/** @var ComponentAttributeBag $attributes */
?>

@props(['filter', 'label' => true])

<div wire:key="wt-{{$this->id()}}-filter-{{$filter->key()}}-wrapper" {{$attributes}}>
    @if($label)
        <label for="wt-{{$this->id()}}-filter-{{$filter->key()}}"
               class="tw-block tw-font-medium tw-text-sm tw-text-gray-500"
        >
            {{$filter->name()}}
        </label>
    @endif

    <select id="wt-{{$this->id()}}-filter-{{$filter->key()}}"
            name="filterValues[{{$filter->key()}}]"
            wire:model.debounce="filterValues.{{$filter->key()}}"
            class="tw-bg-transparent tw-border focus-visible:tw-outline-0 tw-border-solid
                   tw-block tw-border-gray-300 focus:tw-border-indigo-300
                   focus:tw-ring focus:tw-ring-indigo-200 focus:tw-ring-opacity-50
                   tw-rounded-md tw-shadow-sm tw-px-2 tw-py-2 tw-pr-8 tw-w-full tw-min-w-[190px] tw-cursor-pointer"
    >
        <option value="">{{$filter->get(DefStudio\WiredTables\Enums\Config::placeholder)}}</option>
        @foreach($filter->get(DefStudio\WiredTables\Enums\Config::options) as $value => $label)
            <option value="{{$value}}">{{$label}}</option>
        @endforeach
    </select>
</div>

