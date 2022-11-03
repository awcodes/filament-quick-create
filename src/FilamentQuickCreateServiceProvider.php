<?php

namespace FilamentQuickCreate;

use Filament\Facades\Filament;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\PluginServiceProvider;
use FilamentQuickCreate\Facades\QuickCreate as Facade;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\View\View;
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
        $this->app->scoped(Facade::class, function () {
            return new QuickCreate();
        });

        parent::packageRegistered();
    }

    public function boot()
    {
        Livewire::component('quick-create-menu', Http\Livewire\QuickCreateMenu::class);

        Filament::registerRenderHook(
            'user-menu.start',
            fn (): View => view('filament-quick-create::components.quick-create'),
        );

        parent::boot();
    }
}
