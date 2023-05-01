<?php

namespace FilamentQuickCreate\Http\Livewire;

use Filament\Actions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Form;
use FilamentQuickCreate\Facades\QuickCreate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Livewire\Component;

class QuickCreateMenu extends Component implements HasActions
{
    use InteractsWithActions;

    public array $resources = [];

    public function mount(): void
    {
        $this->resources = QuickCreate::getResources();
    }

    public function getActions(): array
    {
        if ($this->resources) {
            return collect($this->resources)
                ->transform(function ($item) {
                    $resource = App::make($item['resource_name']);

                    $action = Actions\Action::make($item['action_name'])
                        ->action(fn() => null)
                        ->label(Str::of($resource->getModelLabel())->ucfirst()->toString())
                        ->extraAttributes(['class' => 'w-full'])
                        ->color('gray')
                        ->model($resource->getModel())
                        ->icon($resource->getNavigationIcon())
                        ->form(fn(Form $form): Form => $resource->form($form->columns(2)))
                        ->modalHeading('test')
                        ->modalSubheading('test');

                    if ($resource->hasPage('create')) {
                        $action->url(fn(): string => $resource->getUrl('create'));
                    }

                    return $action;
                })
                ->toArray();
        }

        return [];
    }

    public function createAction(string $key): Actions\Action
    {
        $resourceInstance = collect($this->resources)->firstWhere('action_name', $key);
        $resource = App::make($resourceInstance['resource_name']);

        $action = Actions\Action::make($key)
            ->action(fn() => null)
            ->label(Str::of($resource->getModelLabel())->ucfirst()->toString())
            ->extraAttributes(['class' => 'w-full'])
            ->color('gray')
            ->model($resource->getModel())
            ->icon($resource->getNavigationIcon())
            ->form(fn(Form $form): Form => $resource->form($form->columns(2)))
            ->modalHeading('test')
            ->modalSubheading('test');

        if ($resource->hasPage('create')) {
            $action->url(fn(): string => $resource->getUrl('create'));
        }

        return $action;
    }

    public function render(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        return view('filament-quick-create::components.create-menu');
    }

    public function getSelectSearchResul()
    {
        // TODO: Implement getSelectSearchResul() method.
    }
}
