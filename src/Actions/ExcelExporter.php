<?php

namespace DefStudio\WiredTables\Actions;

use DefStudio\WiredTables\Elements\Column;
use Illuminate\Support\Collection;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ExcelExporter implements \DefStudio\WiredTables\Contracts\ExcelExporter
{
    /**
     * @inheritDoc
     */
    public function run(string $filename, Collection $rows, Collection $columns)
    {
        $writer = SimpleExcelWriter::streamDownload("$filename.xlsx", writerCallback: function (Writer $writerCallback, string $downloadName) use ($rows, $columns) {
            $writerCallback->openToBrowser($downloadName);

            $writerCallback->getOptions()->setColumnWidth(20, ...range(1, $columns->count()));

            $writerCallback->addRow(new Row($columns->map(fn (Column $column) => Cell::fromValue($column->name()))->toArray()));

            foreach ($rows->values() as $model) {
                $writerCallback->addRow(new Row($columns->map(function (Column $column) use ($model) {

                    $column->setModel($model);

                    $value = $column->renderForExport();

                    if ($value instanceof Cell) {
                        return $value;
                    }

                    $style = match (true) {
                        $value instanceof \DateTimeInterface => (new Style())->setFormat('dd/mm/yyyy'),
                        default => new Style(),
                    };

                    return Cell::fromValue($value, $style);
                })->toArray()));
            }
        });

        return response()->streamDownload(fn () => $writer->close(), "$filename.xlsx");
    }
}
