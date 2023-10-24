<?php

use DefStudio\WiredTables\Elements\Column;
use Illuminate\View\ComponentAttributeBag;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\WiredTable;

/** @var WiredTable $this */
/** @var Column $column */
/** @var ComponentAttributeBag $attributes */


$classes = "uppercase font-medium whitespace-nowrap tracking-wider {$column->getTextClasses()}";
?>

@props(['column'])

<?php

?>
<th scope="col" {{$attributes->class([
                    "px-6 relative",
                    "py-3" => !$this->shouldShowColumnFilters(),
                    "pt-3" => $this->shouldShowColumnFilters(),
                 ])}}>
    @if($column->isSortable())
        <button wire:key="wt-th-sort-{{$column->id()}}"
                wire:click="sort('{{$column->name()}}')"
                class="flex items-center group bg-transparent border-0 {{$classes}}"
        >
            {{$column->name()}}

            <div class="relative">
                @if($this->getSortDirection($column) === \DefStudio\WiredTables\Enums\Sorting::asc)
                    <svg xmlns="http://www.w3.org/2000/svg" @class([
                         "ml-2 h-4 w-4",
                         "stroke-gray-400 fill-gray-400 group-hover:stroke-gray-600 group-hover:fill-gray-600" => !$this->headerConfig(Config::dark_mode),
                         "stroke-gray-400 fill-gray-400 group-hover:stroke-gray-200 group-hover:fill-gray-200" => $this->headerConfig(Config::dark_mode),
                ]) viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @elseif($this->getSortDirection($column) === \DefStudio\WiredTables\Enums\Sorting::desc)
                    <svg xmlns="http://www.w3.org/2000/svg" @class([
                         "ml-2 h-4 w-4",
                         "stroke-gray-400 fill-gray-400 group-hover:stroke-gray-600 group-hover:fill-gray-600" => !$this->headerConfig(Config::dark_mode),
                         "stroke-gray-400 fill-gray-400 group-hover:stroke-gray-200 group-hover:fill-gray-200" => $this->headerConfig(Config::dark_mode),
                ]) viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" @class([
                         "ml-2 h-4 w-4",
                         "stroke-gray-200 fill-gray-200 group-hover:stroke-gray-400 group-hover:fill-gray-400" => !$this->headerConfig(Config::dark_mode),
                         "stroke-gray-600 fill-gray-600 group-hover:stroke-gray-400 group-hover:fill-gray-400" => $this->headerConfig(Config::dark_mode),
                ]) viewBox="0 0 20 20" fill="currentColor">
                        <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                    </svg>
                @endif

                @if(count($this->sorting)>1 && ($position = $this->getSortPosition($column)))
                    <span @class([
                                "absolute -top-3 left-2 font-bold text-[8px] ",
                                "text-gray-400 group-hover:text-gray-200" => !$this->headerConfig(Config::dark_mode),
                                "text-gray-400 group-hover:text-gray-200" => $this->headerConfig(Config::dark_mode),
                        ])>{{$position}}{{date("S", mktime(0, 0, 0, 0, $position, 0))}}</span>
                @endif
            </div>


        </button>
    @else
        <div class="{{$classes}}">
            {{$column->name()}}
        </div>
    @endif
</th>

