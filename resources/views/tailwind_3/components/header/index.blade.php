<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\View\ComponentAttributeBag;

/** @var WiredTable $component */
/** @var ComponentAttributeBag $attributes */
?>

@aware(['component'])

<thead {{$attributes->class("bg-gray-50")}}>
{{$slot}}
</thead>
