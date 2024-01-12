<?php

namespace Awcodes\FilamentQuickCreate\Components;

use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Livewire\Component;

class QuickCreateMenu extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public array $resources = [];

    /**
     * @throws Exception
     */
    public function mount(): void
    {
        $this->resources = QuickCreatePlugin::get()->getResources();
    }

    /**
     * @throws Exception
     */
    public function bootedInteractsWithActions(): void
    {
        $this->cacheActions();
    }

    /**
     * @throws Exception
     */
    protected function cacheActions(): void
    {
        $actions = Action::configureUsing(
            $this->configureAction(...),
            fn (): array => $this->getActions(),
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
        return collect($this->resources)->transform(function ($resource) {
            $r = App::make($resource['resource_name']);

            return CreateAction::make($resource['action_name'])
                ->authorize($r::canCreate())
                ->model($resource['model'])
                ->slideOver(fn (): bool => QuickCreatePlugin::get()->shouldUseSlideOver())
                ->form(function ($arguments, $form) use ($r) {
                    return $r->form($form->operation('create')->columns());
                });
        })
            ->values()
            ->toArray();
    }

    public function shouldBeHidden(): bool
    {
        return QuickCreatePlugin::get()->shouldBeHidden();
    }

    public function render(): View
    {
        return view('filament-quick-create::components.create-menu');
    }
}
