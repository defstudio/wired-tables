<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $component */
/** @var Column $column */
/** @var ComponentAttributeBag $attributes */

?>

@aware(['component'])
@props(['model'])

<tr {{$attributes->merge(['wire:loading.class' => 'opacity-50'])->class(['odd:bg-white even:bg-gray-50' => $component->config(\DefStudio\WiredTables\Enums\Config::striped)])}}>
    {{$slot}}
</tr>

