<?php

namespace FilamentQuickCreate;

use Livewire\Livewire;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use FilamentQuickCreate\Facades\QuickCreate as Facade;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
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
        $resources = collect(Facade::getResources())
            ->filter(function ($resource) {
                return ! in_array($resource, config('filament-quick-create.exclude'));
            })
            ->map(function ($resource) {
                $resource = App::make($resource);
                $route = $resource->getRouteBaseName() . '.create';
                if ($resource->canCreate() && Route::has($route)) {
                    $icon = invade($resource)->getNavigationIcon();
                    return [
                        'label' => Str::ucfirst($resource->getModelLabel()),
                        'icon' => $icon,
                        'url' => route($route)
                    ];
                }
            })
            ->when(Facade::sortingEnabled(), fn($collection) => $collection->sortBy('label'))
            ->values()
            ->toArray();

        return array_filter($resources);
    }

  
}
