<?php

declare(strict_types=1);

namespace Awcodes\QuickCreate;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Livewire\Livewire;

class QuickCreatePlugin implements Plugin
{
    use EvaluatesClosures;

    protected Closure $getResourcesUsing;

    protected array $excludes = [];

    protected array $includes = [];

    protected bool $sort = true;

    protected bool|Closure|null $shouldUseSlideOver = null;

    protected string|Closure|null $sortField = 'label';

    protected bool|Closure $hidden = false;

    protected bool|Closure|null $rounded = null;

    protected string|Closure|null $renderUsingHook = null;

    protected bool|Closure|null $hiddenIcons = null;

    protected string|Closure|null $label = null;

    protected bool|Closure $shouldUseModal = false;

    protected string|array|Closure|null $keyBindings = null;

    protected bool|Closure|null $createAnother = null;

    protected string|array|Closure|null $modalWidths = null;

    protected string|Closure|null $modalHeading = null;

    protected string|Closure|null $modalDescription = null;

    protected array|Closure|null $modalExtraAttributes = null;

    protected string|Closure|null $tooltip = null;

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function boot(Panel $panel): void
    {
        Livewire::component('quick-create-menu', Components\QuickCreateMenu::class);
        $this->getResourcesUsing(fn (): array => $panel->getResources());
    }

    public function excludes(array $resources): static
    {
        $this->excludes = $resources;

        return $this;
    }

    public function includes(array $resources): static
    {
        $this->includes = $resources;

        return $this;
    }

    public function rounded(bool|Closure $condition = true): static
    {
        $this->rounded = $condition;

        return $this;
    }

    public function getId(): string
    {
        return 'quick-create';
    }

    public function getExcludes(): array
    {
        return $this->evaluate($this->excludes);
    }

    public function getIncludes(): array
    {
        return $this->evaluate($this->includes);
    }

    public function getSortField(): string
    {
        return $this->evaluate($this->sortField);
    }

    public function getResources(): array
    {
        $resources = filled($this->getIncludes())
            ? $this->getIncludes()
            : $this->evaluate($this->getResourcesUsing);

        $list = collect($resources)
            ->filter(fn ($item): bool => ! in_array($item, $this->getExcludes()))
            ->map(function ($resourceName): ?array {
                $resource = app($resourceName);

                if (Filament::hasTenancy() && ! Filament::getTenant()) {
                    return null;
                }

                if ($resource->canCreate()) {
                    $actionName = 'create_'.Str::of($resource->getModel())->replace('\\', '')->snake();

                    return [
                        'resource_name' => $resourceName,
                        'label' => Str::ucfirst($resource->getModelLabel()),
                        'model' => $resource->getModel(),
                        'icon' => $resource->getNavigationIcon(),
                        'action_name' => $actionName,
                        'action' => ! $resource->hasPage('create') || $this->shouldUseModal()
                            ? 'mountAction(\''.$actionName.'\')'
                            : null,
                        'url' => $resource->hasPage('create') && ! $this->shouldUseModal()
                            ? $resource::getUrl('create')
                            : null,
                        'navigation' => $resource->getNavigationSort(),
                    ];
                }

                return null;
            })
            ->when($this->isSortable(), fn ($collection) => $collection->sortBy($this->getSortField()))
            ->values()
            ->toArray();

        return array_filter($list);
    }

    public function getResourcesUsing(Closure $callback): static
    {
        $this->getResourcesUsing = $callback;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->evaluate($this->sort);
    }

    public function isRounded(): bool
    {
        return $this->evaluate($this->rounded) ?? true;
    }

    public function register(Panel $panel): void
    {
        $panel
            ->renderHook(
                name: $this->getRenderHook(),
                hook: fn (): string => Blade::render("@livewire('quick-create-menu')")
            );
    }

    public function shouldUseSlideOver(): bool
    {
        return $this->evaluate($this->shouldUseSlideOver) ?? false;
    }

    public function slideOver(bool $condition = true): static
    {
        $this->shouldUseSlideOver = $condition;

        return $this;
    }

    public function sort(bool|Closure $condition = true): static
    {
        $this->sort = $condition;

        return $this;
    }

    public function sortBy(string|Closure $sortBy = 'label'): static
    {
        if (! in_array($sortBy, ['label', 'navigation'])) {
            $sortBy = 'label';
        }

        $this->sortField = $sortBy;

        return $this;
    }

    public function hidden(bool|Closure $hidden = true): static
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function shouldBeHidden(): bool
    {
        return $this->evaluate($this->hidden) ?? false;
    }

    public function renderUsingHook(string|Closure $panelHook): static
    {
        $this->renderUsingHook = $panelHook;

        return $this;
    }

    public function getRenderHook(): string
    {
        return $this->evaluate($this->renderUsingHook) ?? PanelsRenderHook::USER_MENU_BEFORE;
    }

    public function hiddenIcons(bool|Closure $condition = true): static
    {
        $this->hiddenIcons = $condition;

        return $this;
    }

    public function shouldHideIcons(): bool
    {
        return $this->evaluate($this->hiddenIcons) ?? false;
    }

    public function label(string|Closure $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->evaluate($this->label) ?? null;
    }

    public function shouldUseModal(): bool
    {
        return $this->evaluate($this->shouldUseModal) ?? false;
    }

    public function alwaysShowModal(bool|Closure $condition = true): static
    {
        $this->shouldUseModal = $condition;

        return $this;
    }

    public function keyBindings(string|array|Closure|null $bindings): static
    {
        $this->keyBindings = $bindings;

        return $this;
    }

    public function getKeyBindings(): ?array
    {
        return collect($this->evaluate($this->keyBindings))->toArray();
    }

    public function createAnother(bool|Closure $condition = true): static
    {
        $this->createAnother = $condition;

        return $this;
    }

    public function canCreateAnother(): ?bool
    {
        return $this->evaluate($this->createAnother);
    }

    public function modalWidths(string|array|Closure $widths): static
    {
        $this->modalWidths = $widths;

        return $this;
    }

    public function getModalWidth(int $index = 0): ?string
    {
        $widths = $this->evaluate($this->modalWidths);

        if (is_string($widths)) {
            return $widths;
        }

        if (is_array($widths)) {
            return $widths[$index] ?? array_values($widths)[0] ?? null;
        }

        return null;
    }

    public function modalHeading(string|Closure $heading): static
    {
        $this->modalHeading = $heading;

        return $this;
    }

    public function getModalHeading(string $resourceLabel): ?string
    {
        $heading = $this->evaluate($this->modalHeading);

        return $heading ? str_replace(':label', $resourceLabel, $heading) : null;
    }

    public function modalDescription(string|Closure $description): static
    {
        $this->modalDescription = $description;

        return $this;
    }

    public function getModalDescription(string $resourceLabel): ?string
    {
        $description = $this->evaluate($this->modalDescription);

        return $description ? str_replace(':label', $resourceLabel, $description) : null;
    }

    public function modalExtraAttributes(array|Closure $attributes): static
    {
        $this->modalExtraAttributes = $attributes;

        return $this;
    }

    public function getModalExtraAttributes(): ?array
    {
        return $this->evaluate($this->modalExtraAttributes);
    }

    public function tooltip(string|Closure|null $tooltip = null): static
    {
        $this->tooltip = $tooltip ?? __('quick-create::quick-create.button_label');

        return $this;
    }

    public function getTooltip(): ?string
    {
        return $this->evaluate($this->tooltip);
    }
}
