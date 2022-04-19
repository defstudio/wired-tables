<?php

namespace DefStudio\WiredTables\Elements;

class Dump
{
    /** @var mixed[] */
    private array $values = [];
    private string $label;

    public function __construct(mixed ...$value)
    {
        $this->values = $value;
        $this->label = 'Misc';
    }

    public function label(string $label): static
    {
        if (empty($label)) {
            $label = 'Misc';
        }

        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function print(): void
    {
        dump(...$this->values);
    }

    /**
     * @return mixed[]
     */
    public function values(): array
    {
        return $this->values;
    }
}
