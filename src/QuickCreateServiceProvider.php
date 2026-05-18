<?php

declare(strict_types=1);

namespace Awcodes\QuickCreate;

use Awcodes\QuickCreate\Components\QuickCreateMenu;
use Livewire\Livewire;
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

    public function bootingPackage(): void
    {
        Livewire::component('quick-create-menu', QuickCreateMenu::class);
    }
}
