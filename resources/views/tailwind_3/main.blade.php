<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

<x-wired-tables::wrapper :component="$this">
    <x-wired-tables::debug/>

    <x-wired-tables::table>
        <x-slot name="header">
            <x-wired-tables::header>
                <tr>
                    @foreach($this->columns as $column)
                        @php($column->setParentConfiguration($this->configuration()->header))
                        <x-wired-tables::header.th :column="$column"/>
                    @endforeach
                </tr>
            </x-wired-tables::header>
        </x-slot>

        <x-wired-tables::body>
            <tr>
                @foreach($this->columns as $column)
                    @php($column->setParentConfiguration($this->configuration()))
                    <x-wired-tables::body.td :column="$column"/>
                @endforeach
            </tr>
        </x-wired-tables::body>

    </x-wired-tables::table>

</x-wired-tables::wrapper>

