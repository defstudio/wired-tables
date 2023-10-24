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
    "tw-whitespace-nowrap" => !$column->get(\DefStudio\WiredTables\Enums\Config::wrapText) && !$this->config(\DefStudio\WiredTables\Enums\Config::compact_table) && !$column->get(\DefStudio\WiredTables\Enums\Config::limit),
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

$clamp_width = $column->get(\DefStudio\WiredTables\Enums\Config::clamp);

?>
@props(['column', 'model'])

<td x-data="{expanded: false}" wire:key="wt-{{$this->id}}-row-{{$this->getRowId($model)}}-cell" {{$attributes}} @if($clamp_width)style="max-width: {{$clamp_width}}px" @endif>
    @if($url = $column->getUrl())
        <a href="{{$url}}" {{($url_target = $column->get(\DefStudio\WiredTables\Enums\Config::url_target)) ? "target='$url_target'": ''}}>
            {{$content}}
        </a>
    @elseif($column->get(\DefStudio\WiredTables\Enums\Config::limit)  && str($content->toHtml())->toString())
        <div class="tw-flex">
            <div x-show="!expanded" class="tw-truncate">
                {{$content}}
            </div>
            <div x-show="expanded" x-cloak>
                {{$content}}
            </div>
            <div>
                <svg @click="expanded=!expanded" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="tw-ml-2 tw-w-5 tw-h-5" style="cursor: pointer">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    @else
        {{$content}}
    @endif
</td>
