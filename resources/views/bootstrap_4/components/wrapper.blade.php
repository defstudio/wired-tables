<div {{$attributes->class([
    "flex flex-col overflow-x-auto sm:rounded-lg",
    "shadow-[0_0_15px_-2px_rgba(0,0,0,0.1)]" => $this->config(\DefStudio\WiredTables\Enums\Config::drop_shadow)
])}}>
    {{$slot}}
</div>
