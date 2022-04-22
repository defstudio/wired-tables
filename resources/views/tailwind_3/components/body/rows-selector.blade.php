<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */
/** @var Model $model */

?>

@props(['model'])

@if($this->shouldShowRowsSelector())
    <td wire:key="wt-{{$this->id}}-row-{{$this->getRowId($model)}}-selection-cell"
        {{$attributes->class(['pl-6 py-3 text-left'])}}
    >
        <x-wired-tables::elements.checkbox wire:key="wt-{{$this->id}}-{{$this->getRowId($model)}}-row-selection"
                                           wire:model="selection.{{$this->getRowId($model)}}"
        />
    </td>
@endif
