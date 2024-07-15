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
    "px-2 py-1" => $this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "px-6 py-3" => !$this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "font-medium",
    "whitespace-nowrap" => !$column->get(\DefStudio\WiredTables\Enums\Config::wrapText) && !$this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "min-w-[15rem]" => $column->get(\DefStudio\WiredTables\Enums\Config::wrapText) && \Illuminate\Support\Str::of($content->toHtml())->trim()->isNotEmpty(),
    $column->getTextClasses(),
]);

?>

@props(['column', 'model'])

<td wire:key="wt-{{$this->id}}-footer-{{$column->id()}}-cell" {{$attributes}}>
    {{$this->getColumnSum($column->name())}}
</td>

