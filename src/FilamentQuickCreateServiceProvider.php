<?php

namespace FilamentQuickCreate;

use FilamentQuickCreate\Facades\QuickCreate as QuickCreateFacade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentQuickCreateServiceProvider extends PackageServiceProvider
{
    protected static string $name = 'filament-quick-create';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->scoped(QuickCreateFacade::class, function () {
            return new QuickCreate();
        });
    }
}
