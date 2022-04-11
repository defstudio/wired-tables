<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $component */
/** @var Column $column */
/** @var ComponentAttributeBag $attributes */


$classes = "uppercase font-medium whitespace-nowrap tracking-wider {$column->getTextClasses()}";

$sortable = $column->get(Config::is_sortable);
?>

@aware(['component'])
@props(['column'])

<?php

?>
<th {{$attributes->merge(['scope' => 'col'])->class(["px-6 py-3"])}}>
    @if($sortable)
        <button wire:click="sort('{{$column->dbColumn()}}')" class="flex items-center group {{$classes}}">
            {{$column->name()}}

            @if($component->getSortDirection($column) === \DefStudio\WiredTables\Enums\Sorting::asc)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 h-4 w-4 stroke-gray-400 fill-gray-400 group-hover:stroke-gray-600 group-hover:fill-gray-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            @elseif($component->getSortDirection($column) === \DefStudio\WiredTables\Enums\Sorting::desc)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 h-4 w-4 stroke-gray-400 fill-gray-400 group-hover:stroke-gray-600 group-hover:fill-gray-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 h-4 w-4 stroke-gray-200 fill-gray-200 group-hover:stroke-gray-400 group-hover:fill-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                </svg>
            @endif


            <div class="min-w-[30px] h-full">
                @php($position = $component->getSortPosition($column))
                @if($position)
                    <span class="absolute top-1 font-bold text-[8px] text-gray-400 group-hover:text-gray-600">{{$position}}{{date("S", mktime(0, 0, 0, 0, $position, 0))}}</span>
                @endif
            </div>

        </button>
    @else
        <div class="{{$classes}}">
            {{$column->name()}}
        </div>
    @endif
</th>

