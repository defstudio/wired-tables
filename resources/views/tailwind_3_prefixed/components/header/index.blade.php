<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\View\ComponentAttributeBag;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */
?>


<thead {{$attributes->class("tw-bg-gray-50")}}>
    {{$slot}}
</thead>
