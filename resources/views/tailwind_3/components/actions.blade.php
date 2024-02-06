<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

@if($this->shouldShowActionsSelector())
    @php($visibleActions = collect($this->actions)->filter(fn(DefStudio\WiredTables\Elements\Action $action) => $action->isVisible()))
    @if($this->config(\DefStudio\WiredTables\Enums\Config::group_actions))

        <div {{$attributes->class('relative')}} wire:key="wt-{{$this->id()}}-actions-wrapper" x-data="{show_actions: false}">
            <button
                wire:key="wt-{{$this->id()}}-actions-dropdown"
                {{$attributes->class("flex bg-transparent border focus-visible:outline-0 border-solid border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm text-gray-700 px-2 py-2")}}
                @click="show_actions = !show_actions"
            >
                Actions
                <svg xmlns="http://www.w3.org/2000/svg" class=" h-5 w-5 bg-[right_0.5rem_center] bg-[length:1.5em_1.5em] bg-no-repeat stroke-[#6b7280]" fill="none" viewBox="0 0 20 20" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width='1.5' d="M6 8l4 4 4-4"/>
                </svg>
            </button>
            <div wire:key="wt-{{$this->id()}}-actions-container"
                 x-show="show_actions"
                 x-on:click.outside="show_actions = false"
                 class="absolute table right-0 top-[calc(100%_+_10px)] bg-white z-10 shadow-md border border-gray-300 py-0.5 px-1 rounded text-sm text-gray-700"
                 x-cloak
            >

                @foreach($visibleActions->chunk($this->config(\DefStudio\WiredTables\Enums\Config::actions_columns, $visibleActions->count() > 3 ? 3 : 1)) as $action_group)
                    <div class="table-row">
                        @foreach($action_group as $index => $action)
                                <?php /** @var \DefStudio\WiredTables\Elements\Action $action */ ?>
                            <div wire:key="wt-{{$this->id()}}-action-{{$index}}-wrapper" class="table-cell p-1">
                                <button wire:key="wt-{{$this->id()}}-action-{{$index}}"
                                        @click="show_actions = false; $wire.call('{{$action->method()}}' {{$action->methodArguments()->map(fn(string $arg) => ", '$arg'")->join('')}})"
                                        class="bg-transparent border focus-visible:outline-0 border-solid p-2 whitespace-nowrap bg-gray-100 hover:bg-gray-200 w-full rounded focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                >{{$action->name()}}</button>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

    @else
        <div {{$attributes->class('flex gap-1')}} wire:key="wt-{{$this->id()}}-actions-wrapper">
            @foreach($visibleActions as $index => $action)
                    <?php /** @var \DefStudio\WiredTables\Elements\Action $action */ ?>

                <button wire:key="wt-{{$this->id()}}-action-{{$index}}"
                        @click="show_actions = false; $wire.call('{{$action->method()}}' {{$action->methodArguments()->map(fn(string $arg) => ", '$arg'")->join('')}})"
                        class="bg-transparent border focus-visible:outline-0
                               border-solid border-gray-300 p-2 whitespace-nowrap bg-gray-100
                               hover:bg-gray-200 w-full rounded focus:border-indigo-300
                               focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    @php($actionName = $action->name())
                    @if($actionName === '_Excel_')
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path d="M48 448V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/>
                        </svg>
                    @else
                        {{$actionName}}
                    @endif
                </button>

            @endforeach
        </div>
    @endif

@endif
