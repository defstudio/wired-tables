<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\PaginationException;
use DefStudio\WiredTables\WiredTable;

/**
 * @mixin WiredTable
 */
trait HasPagination
{
    public int|string $pageSize;

    public function mountHasPagination(): void
    {
        $this->setPageSize($this->config(Config::default_page_size));
    }

    public function setPageSize(int|string $size): void
    {
        if (! in_array($size, $this->config(Config::available_page_sizes))) {
            PaginationException::unallowedSize($size);
        }

        $this->pageSize = $size;
    }
}
