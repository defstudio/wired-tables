<div {{$attributes->class([
    "tw-min-h-[300px]",
    "tw-flex tw-flex-col sm:tw-rounded-lg",
    "tw-shadow-[0_0_15px_-2px_rgba(0,0,0,0.1)]" => $this->config(\DefStudio\WiredTables\Enums\Config::wrapper_shadow)
])}}>
    {{$slot}}
</div>
