<?php

use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var ComponentAttributeBag $attributes */

?>

<tbody {{$attributes->class(['tw-divide-y tw-divide-gray-200' => $this->config(\DefStudio\WiredTables\Enums\Config::enable_row_dividers)])}}>
{{$slot}}
</tbody>
