<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\View\ComponentAttributeBag;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */
?>


<thead {{$attributes->class([
    "tw-bg-gray-50" => !$this->headerConfig(\DefStudio\WiredTables\Enums\Config::dark_mode),
    "tw-bg-gray-700" => $this->headerConfig(\DefStudio\WiredTables\Enums\Config::dark_mode),
])}}>
    {{$slot}}
</thead>
