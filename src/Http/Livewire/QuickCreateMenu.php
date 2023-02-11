<?php

namespace FilamentQuickCreate\Http\Livewire;

use Filament\Forms\ComponentContainer;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Cancel;
use Filament\Support\Exceptions\Halt;
use FilamentQuickCreate\Facades\QuickCreate;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class QuickCreateMenu extends Page
{
    public $resources;

    public function mount(): void
    {
        $this->resources = QuickCreate::getResources();
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

    public function callMountedAction(?string $arguments = null): ?Action
    {
        $action = $this->getMountedAction();

        if (! $action || $action->isDisabled()) {
            return null;
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
            return null;
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

    public function mountAction(string $name): ?Action
    {
        $this->mountedAction = $name;

        $action = $this->getMountedAction();

        if (! $action) {
            return null;
        }

        if ($action->isDisabled()) {
            return null;
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
            return null;
        } catch (Cancel $exception) {
            $this->mountedAction = null;

            return null;
        }

        if (! $action->shouldOpenModal()) {
            return $this->callMountedAction();
        }

        $this->resetErrorBag();

        $this->dispatchBrowserEvent('open-modal', [
            'id' => 'quick-create-action',
        ]);

        return null;
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


    public function render(): View
    {
        return view('filament-quick-create::components.create-menu');
    }
}
