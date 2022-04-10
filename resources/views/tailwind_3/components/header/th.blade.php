<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;

/** @var Column $column */
/** @var ComponentAttributeBag $attributes */

$attributes = $attributes->merge([
    'scope' => 'col'
])->class([
    "px-6 py-3",
    "font-medium",
    "whitespace-nowrap",
    "tracking-wider",
    $column->getTextClasses(),
]);

$sortable = $column->get(Config::is_sortable);
?>

@aware(['component'])
@props(['column'])

<?php

?>
<th {{$attributes}}>
    @if($sortable)
        <button wire:click="sort('{{$column->dbColumn()}}')">
            {{$column->name()}}
        </button>
    @else
        {{$column->name()}}
    @endif
</th>
