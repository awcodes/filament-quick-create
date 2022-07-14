<?php

namespace FilamentQuickCreate;

use Livewire\Livewire;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;

class FilamentQuickCreateServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-quick-create')
            ->hasViews();
    }

    public function boot()
    {
        Filament::registerRenderHook(
            'global-search.end',
            fn (): View => view('filament-quick-create::components.create-menu', [
                'items' => $this->getFilamentResouces()
            ]),
        );

        parent::boot();
    }

    public function getFilamentResouces()
    {
        $resources = array_map(function($resource) {
            $resource = App::make($resource);
            $route = $resource->getRouteBaseName() . '.create';
            if ($resource->canCreate() && Route::has($route)) {
                $navItems = $resource->getNavigationItems();
                return [
                    'label' => Str::title($resource->getModelLabel()),
                    'icon' => $navItems[0]->getIcon(),
                    'url' => route($route)
                ];
            }
        }, Filament::getResources());

        return array_filter($resources);
    }
}
