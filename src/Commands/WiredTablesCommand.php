<?php

namespace DefStudio\WiredTables\Commands;

use Illuminate\Console\Command;

class WiredTablesCommand extends Command
{
    public $signature = 'wired-tables';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
