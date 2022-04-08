<?php

namespace DefStudio\WiredTables;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WiredTablesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('wired-tables')
            ->hasConfigFile();
    }

    public function packageBooted()
    {
        $this->bootViews();
    }

    protected function bootViews(): void
    {
        $this->publishes([
            $this->package->basePath('/../resources/views') => base_path("resources/views/vendor/{$this->package->shortName()}"),
        ], "{$this->package->shortName()}-views");


        $style = config('wired-tables.style');
        $this->loadViewsFrom($this->package->basePath("/../resources/views/$style"), 'wired-tables');
    }
}
