<?php

namespace FilamentQuickCreate\Http\Livewire;

use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Concerns\HasActions;
use Filament\Pages\Page;
use FilamentQuickCreate\Facades\QuickCreate as QuickCreateFacade;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class QuickCreateMenu extends Page
{
    use HasActions;

    public $resources;

    public function mount(): void
    {
        $this->resources = $this->getFilamentResources();
    }

    protected function getActions(): array
    {
        if ($this->resources) {

            return collect($this->resources)
                ->filter(function($item) {
                    return $item['url'] === null;
                })
                ->transform(function($item) {
                    $resource = App::make($item['resource_name']);
                    $listResource = invade(App::make($resource->getPages()['index']['class']));
                    $form = $listResource->getCreateFormSchema();

                    return CreateAction::make($item['action_name'])
                        ->model($resource->getModel())
                        ->form($form);
                })
                ->values()
                ->toArray();
        }

        return [];
    }

    public function render(): View
    {
        return view('filament-quick-create::components.create-menu');
    }

    public function getFilamentResources(): array
    {
        $resources = collect(QuickCreateFacade::getResources())
            ->filter(function ($resource) {
                return ! in_array($resource, config('filament-quick-create.exclude'));
            })
            ->map(function ($resourceName) {
                $resource = App::make($resourceName);
                if ($resource->canCreate()) {
                    $actionName = 'create' . Str::of($resource->getLabel())->camel();
                    return [
                        'resource_name' => $resourceName,
                        'label' => Str::ucfirst($resource->getModelLabel()),
                        'icon' => invade($resource)->getNavigationIcon(),
                        'action_name' => $actionName,
                        'action' => ! $resource->hasPage('create') ? 'mountAction(\'' . $actionName . '\')' : null,
                        'url' => $resource->hasPage('create') ? $resource::getUrl('create') : null,
                    ];
                }

                return null;
            })
            ->when(QuickCreateFacade::sortingEnabled(), fn ($collection) => $collection->sortBy('label'))
            ->values()
            ->toArray();

        return array_filter($resources);
    }
}