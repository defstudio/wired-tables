<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var Model $model */
/** @var ComponentAttributeBag $attributes */

?>

@props(['model'])

<tr wire:loading.class="tw-opacity-50"
    wire:key="wt-{{$this->id}}-row-{{$this->getRowId($model)}}"
    {{$attributes->class([
            'tw-bg-white' => !$this->config(\DefStudio\WiredTables\Enums\Config::striped),
            'tw-odd:bg-white tw-even:bg-gray-50' => $this->config(\DefStudio\WiredTables\Enums\Config::striped),
            'tw-hover:bg-gray-200' => $this->config(\DefStudio\WiredTables\Enums\Config::hover),
    ])}}
>{{$slot}}</tr>

