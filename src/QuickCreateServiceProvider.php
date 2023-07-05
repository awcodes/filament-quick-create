<?php

namespace Awcodes\FilamentQuickCreate;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class QuickCreateServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-quick-create')
            ->hasTranslations()
            ->hasViews();
    }
}
