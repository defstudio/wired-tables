<?php

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\PaginationException;
use DefStudio\WiredTables\WiredTable;
use Livewire\WithPagination;

/**
 * @mixin WiredTable
 */
trait HasPagination
{
    use WithPagination;

    public int|string $pageSize;

    public function mountHasPagination(): void
    {
        $this->setPageSize($this->config(Config::default_page_size));
    }

    public function updatedPageSize(): void
    {
        $this->resetPage();
    }

    public function setPageSize(int|string $size): void
    {
        if (! in_array($size, $this->config(Config::available_page_sizes))) {
            PaginationException::unallowedSize($size);
        }

        $this->pageSize = $size;
    }

    public function paginationEnabled(): bool
    {
        if (empty(Config::available_page_sizes)) {
            return false;
        }

        return true;
    }
}
