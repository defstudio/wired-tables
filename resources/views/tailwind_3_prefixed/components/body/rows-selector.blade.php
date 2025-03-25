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
    <td wire:key="wt-{{$this->getId()}}-row-{{$this->getRowId($model)}}-selection-cell"
        {{$attributes->class(['tw-pl-6 tw-py-3 tw-text-left'])}}
    >
        <x-wired-tables::elements.checkbox wire:key="wt-{{$this->getId()}}-{{$this->getRowId($model)}}-row-selection"
                                           wire:model="selection.{{$this->getRowId($model)}}"
        />
    </td>
@endif
