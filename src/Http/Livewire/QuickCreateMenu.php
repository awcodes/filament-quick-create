<?php

namespace FilamentQuickCreate\Http\Livewire;

use Filament\Actions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use FilamentQuickCreate\Facades\QuickCreate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Livewire\Component;
use Closure;

class QuickCreateMenu extends Component implements HasActions
{
    use InteractsWithActions;

    public array $resources = [];

    public function mount(): void
    {
        $this->resources = QuickCreate::getResources();
    }

    public function bootedInteractsWithActions(): void
    {
        $this->cacheActions();
    }

    protected function cacheActions(): void
    {
        $actions = Actions\Action::configureUsing(
            Closure::fromCallable([$this, 'configureAction']),
            fn(): array => $this->getActions(),
        );

        foreach ($actions as $action) {
            $this->cacheAction($action);
            $this->cachedActions[$action->getName()] = $action;
        }
    }

    public function getActions(): array
    {
        return collect($this->resources)->transform(function($resource) {
                $r = App::make($resource['resource_name']);
                return Actions\CreateAction::make($resource['action_name'])
                    ->model($resource['model'])
                    ->form(function($form) use ($r) {
                        return $r->form($form->columns());
                    });
            })
            ->values()
            ->toArray();
    }

//    public function createAction(): Actions\Action
//    {
//        $resources = collect($this->resources);
//
//        return Actions\CreateAction::make('quick-create')
//            ->extraAttributes(['class' => 'w-full'])
//            ->color('gray')
//            ->iconSize('sm')
//            ->label(function($arguments) use ($resources) {
//                return $resources->firstWhere('model', $arguments['model'])['label'];
//            })
//            ->model(function($arguments) use ($resources) {
//                return $resources->firstWhere('model', $arguments['model'])['model'];
//            })
//            ->icon(function($arguments) use ($resources) {
//                return $resources->firstWhere('model', $arguments['model'])['icon'];
//            })
//            ->url(function($arguments) use ($resources) {
//                return $resources->firstWhere('model', $arguments['model'])['url'];
//            })
//            ->form(function($arguments, $form) use ($resources) {
//                $resourceInstance = $resources->firstWhere('model', $arguments['model']);
//                $resource = App::make($resourceInstance['resource_name']);
//                return $resource->form($form->columns(2));
//            })
//            ->action(fn() => null);
//    }

    public function render(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        return view('filament-quick-create::components.create-menu');
    }
}
