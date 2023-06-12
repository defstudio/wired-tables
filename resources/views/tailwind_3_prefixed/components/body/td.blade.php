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
    'wire:key' => "wt-$this->id-row-{$this->getRowId($model)}-cell",
    'style' => ($width = $column->get(\DefStudio\WiredTables\Enums\Config::limit)) ? "max-width: {$width}px;" : '',
])->class([
    "tw-px-4 tw-py-2" => $this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "tw-px-6 tw-py-3" => !$this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "tw-whitespace-nowrap" => !$column->get(\DefStudio\WiredTables\Enums\Config::wrapText) && !$this->config(\DefStudio\WiredTables\Enums\Config::compact_table),
    "tw-truncate" => $this->config(\DefStudio\WiredTables\Enums\Config::limit),
    "tw-font-medium",
    "tw-min-w-[15rem]" => $column->get(\DefStudio\WiredTables\Enums\Config::wrapText) && \Illuminate\Support\Str::of($content->toHtml())->trim()->isNotEmpty(),
    $column->getTextClasses(),
]) ;

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

<td x-data="{expanded: false}" wire:key="wt-{{$this->id}}-row-{{$this->getRowId($model)}}-cell" {{$attributes}}>
    @if($url = $column->getUrl())
        <a href="{{$url}}" {{($url_target = $column->get(\DefStudio\WiredTables\Enums\Config::url_target)) ? "target='$url_target'": ''}}>
            {{$content}}
        </a>
    @elseif($column->get(\DefStudio\WiredTables\Enums\Config::limit))
        <div @click="expanded=!expanded" x-show="!expanded" class="tw-truncate">
            {{$content}}
        </div>
        <div @click="expanded=!expanded" x-show="expanded" x-cloak>
            {{$content}}
        </div>
    @else
        {{$content}}
    @endif
</td>
