<?php

namespace FilamentQuickCreate\Components;

use Closure;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Livewire\Component;

class QuickCreateMenu extends Component implements HasActions
{
    use InteractsWithActions;

    public array $resources = [];

    /**
     * @throws Exception
     */
    public function mount(): void
    {
        $this->resources = filament()->getCurrentContext()->getPlugin('filament-quick-create')->getResources();
    }

    public function bootedInteractsWithActions(): void
    {
        $this->cacheActions();
    }

    protected function cacheActions(): void
    {
        $actions = Action::configureUsing(
            Closure::fromCallable([$this, 'configureAction']),
            fn(): array => $this->getActions(),
        );

        foreach ($actions as $action) {
            if (! $action instanceof Action) {
                throw new InvalidArgumentException('Header actions must be an instance of ' . Action::class . ', or ' . ActionGroup::class . '.');
            }
            $this->cacheAction($action);
            $this->cachedActions[$action->getName()] = $action;
        }
    }

    public function getActions(): array
    {
        return collect($this->resources)->transform(function($resource) {
                $r = App::make($resource['resource_name']);
                return CreateAction::make($resource['action_name'])
                    ->authorize($r::canCreate())
                    ->model($resource['model'])
                    ->form(function($arguments, $form) use ($r) {
                        return $r->form($form->operation('create')->columns());
                    });
            })
            ->values()
            ->toArray();
    }

    public function render(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        return view('filament-quick-create::components.create-menu');
    }
}
