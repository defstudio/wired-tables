<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Enums\Config;

trait HasTextConfiguration
{
    public function getTextClasses(): string
    {
        return collect([
            Config::text_align_class,
            Config::text_color_class,
            Config::font_size_class,
        ])->map(fn (Config $config) => $this->get($config))
            ->filter()
            ->join(' ');
    }

    public function fontXs(): static
    {
        return $this->set(Config::font_size_class, 'text-xs');
    }

    public function fontSm(): static
    {
        return $this->set(Config::font_size_class, 'text-sm');
    }

    public function fontBase(): static
    {
        return $this->set(Config::font_size_class, 'text-base');
    }

    public function fontLg(): static
    {
        return $this->set(Config::font_size_class, 'text-lg');
    }

    public function textColorClass(string $class): static
    {
        return $this->set(Config::text_color_class, $class);
    }

    public function textLeft(): static
    {
        return $this->set(Config::text_align_class, 'text-left');
    }

    public function textCenter(): static
    {
        return $this->set(Config::text_align_class, 'text-center');
    }

    public function textRight(): static
    {
        return $this->set(Config::text_align_class, 'text-right');
    }

    public function textJustify(): static
    {
        return $this->set(Config::text_align_class, 'text-justify');
    }

}
