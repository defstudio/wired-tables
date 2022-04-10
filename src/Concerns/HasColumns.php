<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Exceptions\ColumnException;

trait HasColumns
{
    /** @var Column[] */
    public array $columns;
    private bool $columnsLocked = true;

    public function bootHasColumns(): void
    {
        $this->columns = [];

        $this->columnsLocked = false;
        $this->columns();
        $this->columnsLocked = true;

        if(empty($this->columns)){
            throw ColumnException::noColumnDefined(static::class);
        }
    }

    abstract public function columns(): void;

    protected function column(string $name, string $dbColumn = null): Column
    {
        if($this->columnsLocked){
            throw ColumnException::locked();
        }

        $this->columns[] = $column = new Column($name, $dbColumn);
        return $column;
    }
}
