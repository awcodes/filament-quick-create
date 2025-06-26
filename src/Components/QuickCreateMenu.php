<?php

declare(strict_types=1);

namespace Awcodes\QuickCreate\Components;

use Awcodes\QuickCreate\QuickCreatePlugin;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Livewire\Component;
use ReflectionMethod;

class QuickCreateMenu extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public array $resources = [];

    public ?bool $rounded = null;

    public bool $hiddenIcons = false;

    public ?string $label = null;

    public ?array $keyBindings = null;

    public ?string $tooltip = null;

    /**
     * @throws Exception
     */
    public function mount(): void
    {
        $this->resources = QuickCreatePlugin::get()->getResources();
        $this->rounded = QuickCreatePlugin::get()->isRounded();
        $this->hiddenIcons = QuickCreatePlugin::get()->shouldHideIcons();
        $this->label = QuickCreatePlugin::get()->getLabel();
        $this->keyBindings = QuickCreatePlugin::get()->getKeyBindings();
        $this->tooltip = QuickCreatePlugin::get()->getTooltip();
    }

    /**
     * @throws Exception
     */
    public function bootedInteractsWithActions(): void
    {
        $this->cacheActions();
    }

    public function getActions(): Collection
    {
        return collect($this->resources)
            ->transform(function (array $resource, $index): CreateAction {
                $r = App::make($resource['resource_name']);
                $canCreateAnother = QuickCreatePlugin::get()->canCreateAnother();

                if ($canCreateAnother === null) {
                    $canCreateAnother = true;

                    if ($r->hasPage('create')) {
                        $canCreateAnother = App::make($r->getPages()['create']->getPage())->canCreateAnother();
                    } else {
                        $page = isset($r->getPages()['index'])
                            ? $r->getPages()['index']->getPage()
                            : null;

                        if ($page) {
                            $reflectionMethod = new ReflectionMethod($page, 'getHeaderActions');
                            $actions = $reflectionMethod->invoke(new $page());
                            $createAction = collect($actions)->filter(fn ($action): bool => $action instanceof CreateAction)->first();

                            if ($createAction) {
                                $canCreateAnother = $createAction->canCreateAnother();
                            }
                        }
                    }
                }

                return CreateAction::make($resource['action_name'])
                    ->authorize($r::canCreate())
                    ->model($resource['model'])
                    ->slideOver(fn (): bool => QuickCreatePlugin::get()->shouldUseSlideOver())
                    ->modalWidth(fn (): ?string => QuickCreatePlugin::get()->getModalWidth($index))
                    ->modalHeading(fn (): ?string => QuickCreatePlugin::get()->getModalHeading($resource['label']))
                    ->modalDescription(fn (): ?string => QuickCreatePlugin::get()->getModalDescription($resource['label']))
                    ->extraModalWindowAttributes(fn (): array => QuickCreatePlugin::get()->getModalExtraAttributes() ?? [])
                    ->schema(fn ($arguments, $form) => $r->form($form->operation('create')->columns()))
                    ->createAnother($canCreateAnother)
                    ->action(function (array $arguments, Schema $form, CreateAction $action) use ($r): void {
                        $model = $action->getModel();

                        $record = $action->process(function (array $data, HasActions $livewire) use ($model, $action, $r): Model {
                            if ($translatableContentDriver = $livewire->makeFilamentTranslatableContentDriver()) {
                                $record = $translatableContentDriver->makeRecord($model, $data);
                            } else {
                                $record = new $model();
                                $record->fill($data);
                            }

                            if ($relationship = $action->getRelationship()) {
                                /** @phpstan-ignore-next-line */
                                $relationship->save($record);

                                return $record;
                            }

                            if (
                                $r::isScopedToTenant() &&
                                ($tenant = Filament::getTenant())
                            ) {
                                $relationship = $r::getTenantRelationship($tenant);

                                if ($relationship instanceof HasManyThrough) {
                                    $record->save();

                                    return $record;
                                }

                                return $relationship->save($record);
                            }

                            $record->save();

                            return $record;
                        });

                        $action->record($record);
                        $form->model($record)->saveRelationships();

                        if ($arguments['another'] ?? false) {
                            $action->callAfter();
                            $action->sendSuccessNotification();

                            $action->record(null);

                            // Ensure that the form record is anonymized so that relationships aren't loaded.
                            $form->model($model);

                            $form->fill();

                            $action->halt();
                        }

                        $action->success();
                    });
            })
            ->values();
    }

    public function toggleDropdown(): void
    {
        $this->emit('toggleQuickCreateDropdown');
    }

    public function shouldBeHidden(): bool
    {
        return QuickCreatePlugin::get()->shouldBeHidden();
    }

    public function render(): View
    {
        return view('quick-create::components.create-menu')
            ->with([
                'keyBindings' => $this->keyBindings,
            ]);
    }

    /** @throws Exception */
    protected function cacheActions(): void
    {
        foreach ($this->getActions() as $action) {
            if (! $action instanceof Action) {
                throw new InvalidArgumentException('Header actions must be an instance of '.Action::class.', or '.ActionGroup::class.'.');
            }

            $this->cacheAction($action);
            $this->cachedActions[$action->getName()] = $action;
        }
    }
}
