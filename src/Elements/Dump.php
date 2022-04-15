<?php

namespace DefStudio\WiredTables\Elements;

class Dump
{
    /** @var mixed[] */
    private array $values;
    private string $label = 'Misc';

    public function __construct(mixed ...$value)
    {
        $this->values = $value;
    }

    public function label(string $label): static
    {
        $this->label = $label;
        return $this;
    }
}
