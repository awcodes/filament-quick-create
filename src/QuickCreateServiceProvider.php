<?php

declare(strict_types=1);

namespace Awcodes\QuickCreate;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class QuickCreateServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('quick-create')
            ->hasTranslations()
            ->hasViews();
    }
}
