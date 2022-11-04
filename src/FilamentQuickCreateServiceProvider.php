<?php

namespace FilamentQuickCreate;

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use FilamentQuickCreate\Facades\QuickCreate as QuickCreateFacade;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;

class FilamentQuickCreateServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-quick-create')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->scoped(QuickCreateFacade::class, function () {
            return new QuickCreate();
        });

        parent::packageRegistered();
    }

    public function boot()
    {
        Livewire::component('quick-create-menu', Http\Livewire\QuickCreateMenu::class);

        Filament::registerRenderHook(
            'user-menu.start',
            fn (): string => Blade::render('@livewire(\'quick-create-menu\')'),
        );

        parent::boot();
    }
}
