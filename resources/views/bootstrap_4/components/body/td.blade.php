<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var Column $column */
/** @var Model $model */
/** @var ComponentAttributeBag $attributes */

$attributes = $attributes->merge([
        'wire:key' => "wt-$this->id-row-{$this->getRowId($model)}-cell"
    ])->class([
        "px-6 py-3",
        "font-medium",
        "whitespace-nowrap",
        $column->getTextClasses(),
    ]);
?>

@props(['column', 'model'])

<td wire:key="wt-{{$this->id}}-row-{{$this->getRowId($model)}}-cell"
    {{$attributes->class(["px-6 py-3 font-medium whitespace-nowrap", $column->getTextClasses()])}}
>{{$column->render()}}</td>

