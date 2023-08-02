<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

@if($this->shouldShowActionsSelector())
    @php($visibleActions = collect($this->actions)->filter(fn(DefStudio\WiredTables\Elements\Action $action) => $action->isVisible()))
    @if($this->config(\DefStudio\WiredTables\Enums\Config::group_actions))

        <div {{$attributes->class('relative')}} wire:key="wt-{{$this->id()}}-actions-wrapper" x-data="{show: false}">
            <button
                wire:key="wt-{{$this->id()}}-actions-dropdown"
                {{$attributes->class("flex bg-transparent border focus-visible:outline-0 border-solid border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm text-gray-700 px-2 py-2")}}
                @click="show = !show"
            >
                Actions
                <svg xmlns="http://www.w3.org/2000/svg" class=" h-5 w-5 bg-[right_0.5rem_center] bg-[length:1.5em_1.5em] bg-no-repeat stroke-[#6b7280]" fill="none" viewBox="0 0 20 20" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width='1.5' d="M6 8l4 4 4-4"/>
                </svg>
            </button>
            <div wire:key="wt-{{$this->id()}}-actions-container"
                 x-show="show"
                 x-on:click.outside="show = false"
                 class="absolute table right-0 top-[calc(100%_+_10px)] bg-white z-10 shadow-md border border-gray-300 py-0.5 px-1 rounded text-sm text-gray-700"
                 x-cloak
            >

                @foreach($visibleActions->chunk($this->config(\DefStudio\WiredTables\Enums\Config::actions_columns, $visibleActions->count() > 3 ? 3 : 1)) as $action_group)
                    <div class="table-row">
                        @foreach($action_group as $index => $action)
                            <?php /** @var \DefStudio\WiredTables\Elements\Action $action */ ?>
                            <div wire:key="wt-{{$this->id()}}-action-{{$index}}-wrapper" class="table-cell p-1">
                                <button wire:key="wt-{{$this->id()}}-action-{{$index}}"
                                        @click="show = false; $wire.call('{{$action->method()}}' {{$action->methodArguments()->map(fn(string $arg) => ", '$arg'")->join('')}})"
                                        class="bg-transparent border focus-visible:outline-0 border-solid p-2 whitespace-nowrap bg-gray-100 hover:bg-gray-200 w-full rounded focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                >{{$action->name()}}</button>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

    @else
        <div {{$attributes->class('flex')}} wire:key="wt-{{$this->id()}}-actions-wrapper">
            @foreach($visibleActions as $index => $action)
                <?php /** @var \DefStudio\WiredTables\Elements\Action $action */ ?>
                <div wire:key="wt-{{$this->id()}}-action-{{$index}}-wrapper" class="table-cell p-1">
                    <button wire:key="wt-{{$this->id()}}-action-{{$index}}"
                            @click="show = false; $wire.call('{{$action->method()}}' {{$action->methodArguments()->map(fn(string $arg) => ", '$arg'")->join('')}})"
                            class="bg-transparent border focus-visible:outline-0 border-solid p-2 whitespace-nowrap bg-gray-100 hover:bg-gray-200 w-full rounded focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >{{$action->name()}}</button>
                </div>
            @endforeach
        </div>
    @endif

@endif
