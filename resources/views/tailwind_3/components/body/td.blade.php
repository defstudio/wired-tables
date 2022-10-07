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
    "px-6 py-3",
    "font-medium",
    "whitespace-nowrap",
    "whitespace-nowrap" => !$column->get(\DefStudio\WiredTables\Enums\Config::wrapText),
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
?>

@props(['column', 'model'])

<td wire:key="wt-{{$this->id}}-row-{{$this->getRowId($model)}}-cell" {{$attributes}}>
    @if($url = $column->getUrl())
        <a href="{{$url}}" {{($url_target = $column->get(\DefStudio\WiredTables\Enums\Config::url_target)) ? "target='$url_target'": ''}}>
            {{$content}}
        </a>
    @else
        {{$content}}
    @endif
</td>

