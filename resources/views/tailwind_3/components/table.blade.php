<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\View\ComponentAttributeBag;

/** @var WiredTable $component */
/** @var ComponentAttributeBag $attributes */
?>

@aware(['component'])

<table {{$attributes->class("min-w-full divide-y divide-gray-200")}}>
    {{$header}}

    {{$slot}}
</table>
