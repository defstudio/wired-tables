<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

<x-wired-tables::wrapper id="{{$this->slug}}" wire:key="wt-{{$this->id}}-wrapper" class="tw-min-h-[300px]">
    <x-wired-tables::top wire:key="wt-{{$this->id}}-top"/>

    @if($this->allSelected && count($this->selectedIds()) < $this->totalRowsCount)
        <div class="tw-px-6 tw-tracking-wider mb-3">
            <span>
               {{count($this->selectedIds())}} rows selected so far,
            </span>
            <span wire:click="selectAllRows" class="tw-underline tw-cursor-pointer">
                 select all {{$this->totalRowsCount}} rows
            </span>
        </div>

    @endif

    <div @class([
         'tw-overflow-auto tw-mb-3',
         'tw-rounded-md' => $this->config(\DefStudio\WiredTables\Enums\Config::rounded)
    ])>
        <x-wired-tables::table wire:key="wt-{{$this->id}}">
            <x-slot name="header">
                <x-wired-tables::header wire:key="wt-{{$this->id}}-header">
                    <tr>
                        <x-wired-tables::header.rows-selector wire:key="wt-{{$this->id}}-th-rows-selector"/>
                        @foreach($this->columns as $column)
                            @continue(!$column->isVisible())
                            @php($column->setParentConfiguration($this->configuration()->header))
                            <x-wired-tables::header.th wire:key="wt-th-{{$column->id()}}" :column="$column"/>
                        @endforeach
                    </tr>
                    <x-wired-tables::header.filters/>
                </x-wired-tables::header>


            </x-slot>
            <x-wired-tables::body>
                @forelse($this->rows as $model)
                    <x-wired-tables::body.tr :model="$model">
                        <x-wired-tables::body.rows-selector wire:key="wt-{{$this->id}}-th-rows-selector" :model="$model"/>
                        @foreach($this->columns as $column)
                            @continue(!$column->isVisible())
                            @php($column->setParentConfiguration($this->configuration()))
                            @php($column->setModel($model))
                            <x-wired-tables::body.td :model="$model" :column="$column"/>
                        @endforeach
                    </x-wired-tables::body.tr>
                @empty
                    <tr>
                        <td class="tw-px-6 tw-py-3 tw-text-gray-500 tw-text-center" colspan="{{count($this->columns) + ($this->shouldShowRowsSelector() ? 1 : 0)}}">
                            {{$this->config(\DefStudio\WiredTables\Enums\Config::empty_message, __('No data found'))}}
                        </td>
                    </tr>
                @endforelse
            </x-wired-tables::body>

        </x-wired-tables::table>
    </div>

    <x-wired-tables::pagination class="tw-mt-auto" wire:key="wt-{{$this->id}}-pagination"/>
</x-wired-tables::wrapper>

