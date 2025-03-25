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
    "tw-px-4 tw-py-2" => $this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "tw-px-6 tw-py-3" => !$this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "tw-whitespace-nowrap" => !$column->get(\DefStudio\WiredTables\Enums\Config::wrapText) && !$this->config(\DefStudio\WiredTables\Enums\Config::compact_table) && !$column->get(\DefStudio\WiredTables\Enums\Config::limit),
    "tw-font-medium",
    "tw-min-w-[15rem]" => $column->get(\DefStudio\WiredTables\Enums\Config::wrapText) && \Illuminate\Support\Str::of($content->toHtml())->trim()->isNotEmpty(),
    $column->getTextClasses(),
]);

?>

@props(['column', 'model'])

<td wire:key="wt-{{$this->getId()}}-footer-{{$column->id()}}-cell" {{$attributes}}>
    {{$this->getColumnSum($column->name())}}
</td>

