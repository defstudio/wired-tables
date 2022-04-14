<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */

?>

@if($this->shouldShowRowsSelector())
    <th {{$attributes->merge(['scope' => 'col'])->class(['px-6 py-3'])}}>
        <input type="checkbox">
    </th>
@endif
