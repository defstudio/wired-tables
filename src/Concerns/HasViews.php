<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Exceptions\TemplateException;
use DefStudio\WiredTables\WiredTable;

/**
 * @mixin WiredTable
 */
trait HasViews
{
    public function mainView(): string
    {
        return $this->configuration->template('main');
    }

    public function tableView(): string
    {
        return $this->configuration->template('table');
    }

    public function headerView(): string
    {
        return $this->configuration->template('header');
    }


}
