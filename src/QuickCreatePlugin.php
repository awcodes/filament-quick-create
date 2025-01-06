<?php

namespace Awcodes\FilamentQuickCreate;

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

    protected string|Closure $sortBy = 'label';

    protected bool|Closure $hidden = false;

    protected bool|Closure|null $rounded = null;

    protected string|Closure|null $renderUsingHook = null;

    protected bool|Closure|null $hiddenIcons = null;

    protected string|Closure|null $label = null;

    protected bool|Closure $shouldUseModal = false;

    protected string|array|Closure|null $keyBindings = null;

    protected bool|Closure|null $createAnother = null;

    public function boot(Panel $panel): void
    {
        Livewire::component('quick-create-menu', Components\QuickCreateMenu::class);
        $this->getResourcesUsing(fn() => $panel->getResources());
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

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'filament-quick-create';
    }

    public function getExcludes(): array
    {
        return $this->evaluate($this->excludes);
    }

    public function getIncludes(): array
    {
        return $this->evaluate($this->includes);
    }

    public function getResources(): array
    {
        $resources = filled($this->getIncludes())
            ? $this->getIncludes()
            : $this->evaluate($this->getResourcesUsing);

        $list = collect($resources)
            ->filter(function ($item) {
                return !in_array($item, $this->getExcludes());
            })
            ->map(function ($resourceName): ?array {
                $resource = app($resourceName);

                if (Filament::hasTenancy() && !Filament::getTenant()) {
                    return null;
                }

                if ($resource->canCreate()) {
                    $actionName = 'create_' . Str::of($resource->getModel())->replace('\\', '')->snake();

                    return [
                        'resource_name' => $resourceName,
                        'label'         => Str::ucfirst(method_exists($resource, 'getQuickCreateLabel') ? call_user_func_array([$resource, 'getQuickCreateLabel'], []) : $resource->getModelLabel()),
                        'model'         => $resource->getModel(),
                        'icon'          => $resource->getNavigationIcon(),
                        'action_name'   => $actionName,
                        'action'        => !$resource->hasPage('create') || $this->shouldUseModal() ? 'mountAction(\'' . $actionName . '\')' : null,
                        'url'           => $resource->hasPage('create') && !$this->shouldUseModal() ? $resource::getUrl('create') : null,
                        'navigation'    => $resource->getNavigationSort(),
                    ];
                }

                return null;
            })
            ->when($this->isSortable(), fn($collection) => $collection->sortBy($this->sortBy))
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

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->renderHook(
                name: $this->getRenderHook(),
                hook: fn(): string => Blade::render("@livewire('quick-create-menu')")
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
        if (!in_array($sortBy, ['label', 'navigation'])) {
            $sortBy = 'label';
        }
        $this->sortBy = $sortBy;

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
}
