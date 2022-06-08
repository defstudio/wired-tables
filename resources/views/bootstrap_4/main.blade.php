<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

<x-wired-tables::wrapper wire:key="wt-{{$this->id}}-wrapper">
    <x-wired-tables::top wire:key="wt-{{$this->id}}-top"/>

    <x-wired-tables::table wire:key="wt-{{$this->id}}" class="my-2">
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
                    <td class="px-4 py-3 text-muted text-center" colspan="{{count($this->columns) + ($this->shouldShowRowsSelector() ? 1 : 0)}}">No data found</td>
                </tr>
            @endforelse
        </x-wired-tables::body>

    </x-wired-tables::table>

    <x-wired-tables::pagination class="mt-auto" wire:key="wt-{{$this->id}}-pagination"/>
</x-wired-tables::wrapper>
