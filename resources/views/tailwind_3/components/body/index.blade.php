<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $component */
/** @var ComponentAttributeBag $attributes */

?>

@aware(['component'])

<tbody {{$attributes->class(['divide-y divide-gray-200' => $component->config(\DefStudio\WiredTables\Enums\Config::enable_row_dividers)])}}>
{{$slot}}
</tbody>
