<?php /** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Configurations;

use DefStudio\WiredTables\Concerns\HasTextConfiguration;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\TemplateException;

class TableConfiguration extends Configuration
{
    use HasTextConfiguration;

    public array $templates = [
        'main' => 'wired-tables::main',
        'table' => 'wired-tables::table',
        'header' => 'wired-tables::header',
    ];

    public HeaderConfiguration $header;

    public function __construct()
    {
        $this->header = new HeaderConfiguration($this);
        $this->initDefaults();
    }

    private function initDefaults(): void
    {
        $this->fontBase()
            ->textLeft()
            ->textColorClass('text-gray-800');
    }

    public function multipleSorting(): static
    {
        return $this->set(Config::support_multiple_sorting, true);
    }

    public function template(string $key): string
    {
        return $this->templates[$key] ?? throw TemplateException::invalidTemplate($key);
    }

    public function overrideTemplate(string $key, string $viewName): static
    {
        if (!in_array($key, $this->templates)) {
            throw TemplateException::invalidTemplate($key);
        }

        $this->templates[$key] = $viewName;

        return $this;
    }
}
