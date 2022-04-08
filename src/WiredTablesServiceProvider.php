<?php

namespace DefStudio\WiredTables;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use DefStudio\WiredTables\Commands\WiredTablesCommand;

class WiredTablesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('wired-tables')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_wired-tables_table')
            ->hasCommand(WiredTablesCommand::class);
    }
}
