<?php

use DefStudio\WiredTables\WiredTable;
use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Enums\Config;

/** @var WiredTable $this */
?>

<x-wired-tables::wrapper id="{{$this->slug}}" wire:key="wt-{{$this->id}}-wrapper">
    <x-wired-tables::top wire:key="wt-{{$this->id}}-top"/>

    @if(count($this->selectedIds())>0 && count($this->selectedIds()) < $this->totalRowsCount && $this->rows->hasPages())
        <div class="px-6 tracking-wider mb-3">
            <span>
               {{count($this->selectedIds())}} rows selected so far,
            </span>
            <span wire:click="selectAllRows" class="underline cursor-pointer">
                 select all {{$this->totalRowsCount}} rows
            </span>
        </div>

    @endif

    <div {{($poll = $this->config(Config::poll)) ? "wire:poll.{$poll}ms" : ""}}
        @class([
           'overflow-auto mb-3',
           'rounded-md' => $this->config(Config::rounded),
           'shadow-md' => $this->config(Config::table_shadow),
        ])
    >
        <x-wired-tables::table wire:key="wt-{{$this->id}}" class="">
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
                        <td class="px-6 py-3 text-gray-500 text-center" colspan="{{count($this->columns) + ($this->shouldShowRowsSelector() ? 1 : 0)}}">
                            {{$this->config(Config::empty_message, __('No data found'))}}
                        </td>
                    </tr>
                @endforelse
            </x-wired-tables::body>

            <x-slot name="footer">
                @if(collect($this->columns)->some(fn(Column $column) => !!$column->get(Config::with_sum)))
                    <x-wired-tables::footer wire:key="wt-{{$this->id}}-footer">
                        <tr class="border-t">
                            @if($this->shouldShowRowsSelector())
                                <td></td>
                            @endif

                            @foreach($this->columns as $column)
                                @continue(!$column->isVisible())

                                <x-wired-tables::footer.td :column="$column"/>
                            @endforeach
                        </tr>
                    </x-wired-tables::footer>
                @endif

            </x-slot>

        </x-wired-tables::table>
    </div>

    <x-wired-tables::pagination class="mt-auto" wire:key="wt-{{$this->id}}-pagination"/>

</x-wired-tables::wrapper>

