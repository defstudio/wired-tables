<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var Column $column */
/** @var Model $model */
/** @var ComponentAttributeBag $attributes */


$content = $column->render();

$attributes = $attributes->merge([
    'wire:key' => "wt-$this->id-row-{$this->getRowId($model)}-cell"
])->class([
    "px-2 py-1" => $this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "px-6 py-3" => !$this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "font-medium",
    "whitespace-nowrap" => !$column->get(\DefStudio\WiredTables\Enums\Config::wrapText) && !$this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "min-w-[15rem]" => $column->get(\DefStudio\WiredTables\Enums\Config::wrapText) && \Illuminate\Support\Str::of($content->toHtml())->trim()->isNotEmpty(),
    $column->getTextClasses(),
]);


if ($emit = $column->getEmit()) {
    $emit = \Illuminate\Support\Arr::wrap($emit);

    $params = collect($emit)
        ->map(fn ($value) => is_array($value) ? json_encode($value) : "'$value'")
        ->join(',');

    $attributes = $attributes->merge([
        'wire:click' => "\$emit($params)",
        'class' => 'cursor-pointer',
    ]);
}

$clamp_width = $column->get(\DefStudio\WiredTables\Enums\Config::clamp);
?>

@props(['column', 'model'])

<td wire:key="wt-{{$this->id}}-row-{{$this->getRowId($model)}}-cell" {{$attributes}} @if($clamp_width)style="max-width: {{$clamp_width}}px" @endif>
    @if($url = $column->getUrl())
        <a href="{{$url}}" {{($url_target = $column->get(\DefStudio\WiredTables\Enums\Config::url_target)) ? "target='$url_target'": ''}}>
            <x-wired-tables::body.content :column="$column">
                {{$content}}
            </x-wired-tables::body.content>
        </a>
    @else
        <x-wired-tables::body.content :column="$column">
            {{$content}}
        </x-wired-tables::body.content>
    @endif
</td>

