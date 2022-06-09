<div {{$attributes->class([
    "tw-flex tw-flex-col tw-overflow-x-auto sm:tw-rounded-lg",
    "tw-shadow-[0_0_15px_-2px_rgba(0,0,0,0.1)]" => $this->config(\DefStudio\WiredTables\Enums\Config::drop_shadow)
])}}>
    {{$slot}}
</div>
