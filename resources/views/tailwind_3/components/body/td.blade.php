<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var Column $column */
/** @var ComponentAttributeBag $attributes */

$attributes = $attributes->class([
    "px-6 py-3",
    "font-medium",
    "whitespace-nowrap",
    $column->getTextClasses(),
]);
?>

@props(['column'])

<?php

?>
<td {{$attributes}}>
    {{$column->render()}}
</td>

