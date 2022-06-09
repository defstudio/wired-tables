<?php

/** @noinspection PhpUnhandledExceptionInspection */

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
    protected string $paginationTheme = 'tailwind';

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
        if ($size !== 'all' && !in_array($size, $this->config(Config::available_page_sizes))) {
            throw PaginationException::unallowedSize($size);
        }

        $this->pageSize = $size;
        $this->updatedPageSize();
    }

    public function paginationEnabled(): bool
    {
        if (empty($this->config(Config::available_page_sizes))) {
            return false;
        }

        return true;
    }
}
