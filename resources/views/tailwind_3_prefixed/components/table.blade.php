<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\View\ComponentAttributeBag;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */
?>

<table {{$attributes->class([
    "min-w-full",
    'divide-y divide-gray-200' => $this->config(\DefStudio\WiredTables\Enums\Config::enable_row_dividers)
])}}>
    {{$header}}

    {{$slot}}
</table>
