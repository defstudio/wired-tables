<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\View\ComponentAttributeBag;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */
?>


<tfoot {{$attributes->class([
    "bg-gray-50" => !$this->headerConfig(\DefStudio\WiredTables\Enums\Config::dark_mode),
    "bg-gray-700" => $this->headerConfig(\DefStudio\WiredTables\Enums\Config::dark_mode),
])}}>
    {{$slot}}
</tfoot>
