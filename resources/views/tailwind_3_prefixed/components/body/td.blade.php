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
        "tw-px-6 tw-py-3",
        "tw-font-medium",
        "tw-whitespace-nowrap",
        $column->getTextClasses(),
    ]);
?>

@props(['column', 'model'])

<td wire:key="wt-{{$this->id}}-row-{{$this->getRowId($model)}}-cell"
    {{$attributes->class(["tw-px-6 tw-py-3 tw-font-medium tw-whitespace-nowrap", $column->getTextClasses()])}}
>
    @if($url = $column->getUrl())
        <a href="{{$url}}" {{($url_target = \DefStudio\WiredTables\Enums\Config::url_target) ? "target='$url_target'": ''}}>
            {{$column->render()}}
        </a>
    @else
        {{$column->render()}}
    @endif
</td>

