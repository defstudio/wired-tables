<?php
/** @var \Illuminate\View\ComponentAttributeBag $attributes */
?>

<div {{$attributes->class([
    "d-flex flex-col shadow-sm rounded-md",
])->merge([
    'style' => "min-height: 300px; overflow-x: auto;"
])}}>
    {{$slot}}
</div>
