<?php

namespace DefStudio\WiredTables\Actions;

use DefStudio\WiredTables\Elements\Column;
use Illuminate\Support\Collection;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ExcelExporter implements \DefStudio\WiredTables\Contracts\ExcelExporter
{
    /**
     * @inheritDoc
     */
    public function run(string $filename, Collection $rows, Collection $columns)
    {
        $writer = SimpleExcelWriter::streamDownload("$filename.xlsx")
            ->addHeader($columns->map(fn (Column $column) => $column->name())->toArray());

        foreach ($rows as $model) {
            $writer = $writer->addRow($columns->map(function (Column $column) use ($model) {
                $column->setModel($model);

                return trim(strip_tags($column->renderForExport()));
            })->toArray());
        }


        return response()->streamDownload(fn () => $writer->close(), "$filename.xlsx");
    }
}
