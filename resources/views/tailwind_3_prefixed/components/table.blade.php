<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\View\ComponentAttributeBag;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */
?>

<table {{$attributes->class([
    "tw-min-w-full",
    'tw-divide-y tw-divide-gray-200' => $this->config(\DefStudio\WiredTables\Enums\Config::enable_row_dividers)
])}}>
    {{$header}}

    {{$slot}}
</table>
