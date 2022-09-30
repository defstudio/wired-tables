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

    public int|string|null $pageSize = null;
    protected string $paginationTheme = 'tailwind';

    public function bootedHasPagination(): void
    {
        $pageSize = $this->getState('page-size', $this->pageSize ?? $this->config(Config::default_page_size));
        $this->setPageSize($pageSize);
    }

    public function updatedPageSize(): void
    {
        $this->resetPage();
        $this->storeState('page-size', $this->pageSize);
    }

    public function setPageSize(int|string $size): void
    {
        if ($size !== 'all' && !in_array($size, $this->config(Config::available_page_sizes))) {
            throw PaginationException::unallowedSize($size);
        }

        $oldPageSize = $this->pageSize;
        $this->pageSize = $size;

        if($this->pageSize !== $oldPageSize){
            $this->updatedPageSize();
        }
    }

    public function paginationEnabled(): bool
    {
        if (empty($this->config(Config::available_page_sizes))) {
            return false;
        }

        return true;
    }
}
