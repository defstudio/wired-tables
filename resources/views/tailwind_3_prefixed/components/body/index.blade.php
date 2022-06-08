<?php

use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */

?>

<tbody {{$attributes->class(['divide-y divide-gray-200' => $this->config(\DefStudio\WiredTables\Enums\Config::enable_row_dividers)])}}>
{{$slot}}
</tbody>
