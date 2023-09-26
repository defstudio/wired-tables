<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */

?>

@if($this->shouldShowRowsSelector())
    <th scope="col"
        wire:key="wt-{{$this->id}}-select-all-header"
            {{$attributes->class([
                       "tw-px-6 tw-text-left tw-align-middle",
                       "tw-py-3" => !$this->shouldShowColumnFilters(),
                       "tw-pt-3" => $this->shouldShowColumnFilters(),
                    ])}}
    >
            <?php
            if (empty($this->selectedIds()) || collect($this->getVisibleRowsIds())->reject(fn (int $id) => collect($this->selectedIds())->contains($id))->isNotEmpty()) {
                $this->allSelected = false;
            } else {
                $this->allSelected = true;
            }
            ?>
        <x-wired-tables::elements.checkbox wire:key="wt-{{$this->id}}-select-all" wire:model="allSelected"/>
    </th>
@endif
