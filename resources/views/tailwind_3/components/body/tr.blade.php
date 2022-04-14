<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var Column $column */
/** @var ComponentAttributeBag $attributes */

?>

@props(['model'])

<tr {{$attributes->merge(['wire:loading.class' => 'opacity-50'])->class(['odd:bg-white even:bg-gray-50' => $this->config(\DefStudio\WiredTables\Enums\Config::striped)])}}>
    {{$slot}}
</tr>

