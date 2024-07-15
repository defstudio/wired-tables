<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Model;

/** @var WiredTable $this */
/** @var Column $column */
/** @var Model $model */
/** @var ComponentAttributeBag $attributes */


$attributes = $attributes->class([
    "px-6 py-3",
    "font-medium",
    "whitespace-nowrap" => !$column->get(\DefStudio\WiredTables\Enums\Config::wrapText),
    $column->getTextClasses(),
]);

?>

@props(['column', 'model'])

<td wire:key="wt-{{$this->id}}-footer-{{$column->id()}}-cell" {{$attributes}}>
    @if($format = $column->get(\DefStudio\WiredTables\Enums\Config::sum_format))
        {{$format($this->getColumnSum($column->name()))}}
    @else
    {{$this->getColumnSum($column->name())}}
    @endif
</td>

