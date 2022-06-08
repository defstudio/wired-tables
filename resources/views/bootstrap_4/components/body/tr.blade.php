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

<tr wire:loading.class="opacity-50"
    wire:key="wt-{{$this->id}}-row-{{$this->getRowId($model)}}"
    {{$attributes->class([
            'bg-white' => !$this->config(\DefStudio\WiredTables\Enums\Config::striped),
            'odd:bg-white even:bg-gray-50' => $this->config(\DefStudio\WiredTables\Enums\Config::striped),
            'hover:bg-gray-200' => $this->config(\DefStudio\WiredTables\Enums\Config::hover),
    ])}}
>{{$slot}}</tr>

