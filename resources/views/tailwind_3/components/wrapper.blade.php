<div {{$attributes->class([
    "min-h-[300px]",
    "flex flex-col sm:rounded-lg",
    "shadow-[0_0_15px_-2px_rgba(0,0,0,0.1)]" => $this->config(\DefStudio\WiredTables\Enums\Config::wrapper_shadow),
])}}>
    {{$slot}}

    @if($footerView = $this->config(\DefStudio\WiredTables\Enums\Config::footer_view))
        @include($footerView)
    @endif
</div>
