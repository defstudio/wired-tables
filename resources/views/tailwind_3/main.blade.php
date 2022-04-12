<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

<x-wired-tables::wrapper wire:key="wt-wrapper-{{$this->id}}" :component="$this">
    <x-wired-tables::debug wire:key="wt-debug-{{$this->id}}"/>

    <x-wired-tables::table wire:key="wt-{{$this->id}}">
        <x-slot name="header">
            <x-wired-tables::header wire:key="wt-header-{{$this->id}}">
                <tr>
                    @foreach($this->columns as $column)
                        @php($column->setParentConfiguration($this->configuration()->header))
                        <x-wired-tables::header.th  wire:key="wt-th-{{$column->id()}}" :column="$column"/>
                    @endforeach
                </tr>
            </x-wired-tables::header>
        </x-slot>
        <x-wired-tables::body>

            @foreach($this->rows as $model)
                <x-wired-tables::body.tr :model="$model">
                    @foreach($this->columns as $column)
                        @php($column->setParentConfiguration($this->configuration()))
                        @php($column->setModel($model))
                        <x-wired-tables::body.td :column="$column"/>
                    @endforeach
                </x-wired-tables::body.tr>
            @endforeach
        </x-wired-tables::body>

    </x-wired-tables::table>

    <x-wired-tables::pagination />

</x-wired-tables::wrapper>

