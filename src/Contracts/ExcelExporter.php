<?php

namespace DefStudio\WiredTables\Contracts;

use DefStudio\WiredTables\Elements\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ExcelExporter
{
    /**
     * @param Collection<int, Model> $rows
     * @param Collection<int, Column> $columns
     */
    public function run(string $filename, Collection $rows, Collection $columns): void;
}
