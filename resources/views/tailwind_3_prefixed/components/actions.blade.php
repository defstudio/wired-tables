<?php

use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
?>

@if($this->shouldShowActionsSelector())
    <div {{$attributes->class('tw-relative')}} wire:key="wt-{{$this->id}}-actions-wrapper" x-data="{show: false}">
        <button
            wire:key="wt-{{$this->id}}-actions-dropdown"
            {{$attributes->class("tw-flex tw-border tw-border-gray-300 focus:tw-border-indigo-300 focus:tw-ring focus:tw-ring-indigo-200 focus:tw-ring-opacity-50 tw-rounded-md tw-shadow-sm tw-text-sm tw-text-gray-700 tw-px-2 tw-py-2")}}
            @click="show = !show"
        >
            Actions
            <svg xmlns="http://www.w3.org/2000/svg" class="tw-h-5 tw-w-5 tw-bg-[right_0.5rem_center] tw-bg-[length:1.5em_1.5em] tw-bg-no-repeat tw-stroke-[#6b7280]" fill="none" viewBox="0 0 20 20" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width='1.5' d="M6 8l4 4 4-4"/>
            </svg>
        </button>
        <div wire:key="wt-{{$this->id}}-actions-container"
             x-show="show"
             x-on:click.outside="show = false"
             class="tw-absolute tw-table tw-right-0 tw-top-[calc(100%_+_10px)] tw-bg-white tw-z-10 tw-shadow-md tw-border tw-border-gray-300 tw-py-0.5 tw-px-1 tw-rounded tw-text-sm tw-text-gray-700"
             x-cloak
        >
            @php($visibleActions = collect($this->actions)->filter(fn(DefStudio\WiredTables\Elements\Action $action) => $action->isVisible()))
            @foreach($visibleActions->chunk($this->config(\DefStudio\WiredTables\Enums\Config::actions_columns, $visibleActions->count() > 3 ? 3 : 1)) as $action_group)
                <div class="tw-table-row">
                    @foreach($action_group as $index => $action)
                        <?php /** @var \DefStudio\WiredTables\Elements\Action $action */ ?>
                        <div wire:key="wt-{{$this->id}}-action-{{$index}}-wrapper" class="tw-table-cell tw-p-1">
                            <button wire:key="wt-{{$this->id}}-action-{{$index}}"
                                    @click="show = false; $wire.call('{{$action->method()}}' {{$action->methodArguments()->map(fn(string $arg) => ", '$arg'")->join('')}})"
                                    class="tw-p-2 tw-whitespace-nowrap tw-bg-gray-100 hover:tw-bg-gray-200 tw-w-full tw-rounded focus:tw-border-indigo-300 focus:tw-ring focus:tw-ring-indigo-200 focus:tw-ring-opacity-50"
                            >{{$action->name()}}</button>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

@endif
