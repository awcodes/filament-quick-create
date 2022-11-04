<?php

namespace FilamentQuickCreate\Http\Livewire;

use Filament\Forms\ComponentContainer;
use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Cancel;
use Filament\Support\Exceptions\Halt;
use FilamentQuickCreate\Facades\QuickCreate as QuickCreateFacade;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuickCreateMenu extends Page
{
    public $resources;

    public function mount(): void
    {
        $this->resources = $this->getFilamentResources();
    }

    protected function getActions(): array
    {
        if ($this->resources) {
            return collect($this->resources)
                ->filter(function ($item) {
                    return $item['url'] === null;
                })
                ->transform(function ($item) {
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

    public function callMountedAction(?string $arguments = null)
    {
        $action = $this->getMountedAction();

        if (! $action) {
            return;
        }

        if ($action->isDisabled()) {
            return;
        }

        $action->arguments($arguments ? json_decode($arguments, associative: true) : []);

        $form = $this->getMountedActionForm();

        $result = null;

        try {
            if ($action->hasForm()) {
                $action->callBeforeFormValidated();

                $action->formData($form->getState());

                $action->callAfterFormValidated();
            }

            $action->callBefore();

            $result = $action->call([
                'form' => $form,
            ]);

            $result = $action->callAfter() ?? $result;
        } catch (Halt $exception) {
            return;
        } catch (Cancel $exception) {
        }

        $this->mountedAction = null;

        $action->resetArguments();
        $action->resetFormData();

        $this->dispatchBrowserEvent('close-modal', [
            'id' => 'quick-create-action',
        ]);

        return $result;
    }

    public function mountAction(string $name)
    {
        $this->mountedAction = $name;

        $action = $this->getMountedAction();

        if (! $action) {
            return;
        }

        if ($action->isDisabled()) {
            return;
        }

        $this->cacheForm(
            'mountedActionForm',
            fn () => $this->getMountedActionForm(),
        );

        try {
            if ($action->hasForm()) {
                $action->callBeforeFormFilled();
            }

            $action->mount([
                'form' => $this->getMountedActionForm(),
            ]);

            if ($action->hasForm()) {
                $action->callAfterFormFilled();
            }
        } catch (Halt $exception) {
            return;
        } catch (Cancel $exception) {
            $this->mountedAction = null;

            return;
        }

        if (! $action->shouldOpenModal()) {
            return $this->callMountedAction();
        }

        $this->resetErrorBag();

        $this->dispatchBrowserEvent('open-modal', [
            'id' => 'quick-create-action',
        ]);
    }

    public function getMountedActionForm(): ?ComponentContainer
    {
        $action = $this->getMountedAction();

        if (! $action) {
            return null;
        }

        if ((! $this->isCachingForms) && $this->hasCachedForm('mountedActionForm')) {
            return $this->getCachedForm('mountedActionForm');
        }

        return $this->makeForm()
            ->schema($action->getFormSchema())
            ->statePath('mountedActionData')
            ->model($action->getModel())
            ->context($this->mountedAction);
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
                    $actionName = 'create'.Str::of($resource->getModelLabel())->camel();

                    return [
                        'resource_name' => $resourceName,
                        'label' => Str::ucfirst($resource->getModelLabel()),
                        'icon' => invade($resource)->getNavigationIcon(),
                        'action_name' => $actionName,
                        'action' => ! $resource->hasPage('create') ? 'mountAction(\''.$actionName.'\')' : null,
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

    public function render(): View
    {
        return view('filament-quick-create::components.create-menu');
    }
}
