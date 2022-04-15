<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Dump;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Support\Collection;

/**
 * @mixin WiredTable
 */
trait DumpsValues
{
    /** @var Dump[]  */
    private array $dumps = [];

    public function dump(mixed ...$value): Dump
    {
        $dump = new Dump(...$value);
        $this->dumps[] = $dump;
        return $dump;
    }

    public function dumpLabels(): Collection
    {
        return collect($this->dumps)
            ->map(fn(Dump $dump) => $dump->getLabel())
            ->unique();
    }

    public function dumps(): Collection
    {
        return collect($this->dumps);
    }
}
