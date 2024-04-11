@props(['column'])

@if($column->get(\DefStudio\WiredTables\Enums\Config::clamp))
    <div class="group relative cursor-default">
        <div class="peer line-clamp-1 opacity-100 hover:opacity-0 whitespace-normal">
            {{$slot}}
        </div>
        <div class="z-10 pointer-events-none p-2 bg-white opacity-0 peer-hover:opacity-100 absolute top-[calc(-0.5rem-1px)] left-[calc(-0.5rem-1px)] right-[calc(-0.5rem-1px)] z-9 box-content border rounded-md whitespace-normal">
            {{$slot}}
        </div>
    </div>
@else
    {{$slot}}
@endif
