<?php

namespace Awcodes\FilamentQuickCreate;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
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

    protected bool | Closure | null $shouldUseSlideOver = null;

    protected string | Closure $sortBy = 'label';

    public function boot(Panel $panel): void
    {
        Livewire::component('quick-create-menu', Components\QuickCreateMenu::class);

        $this->getResourcesUsing(fn () => $panel->getResources());
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
                return ! in_array($item, $this->getExcludes());
            })
            ->map(function ($resourceName): ?array {
                $resource = app($resourceName);

                if (Filament::hasTenancy() && ! Filament::getTenant()) {
                    return null;
                }

                if ($resource->canCreate()) {
                    $actionName = 'create_' . Str::of($resource->getModel())->replace('\\', '')->snake();

                    return [
                        'resource_name' => $resourceName,
                        'label' => Str::ucfirst($resource->getModelLabel()),
                        'model' => $resource->getModel(),
                        'icon' => $resource->getNavigationIcon(),
                        'action_name' => $actionName,
                        'action' => ! $resource->hasPage('create') ? 'mountAction(\'' . $actionName . '\')' : null,
                        'url' => $resource->hasPage('create') ? $resource::getUrl('create') : null,
                        'navigation' => $resource->getNavigationSort(),
                    ];
                }

                return null;
            })
            ->when($this->isSortable(), fn ($collection) => $collection->sortBy($this->sortBy))
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

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->renderHook(
                name: 'panels::user-menu.before',
                hook: fn (): string => Blade::render('@livewire(\'quick-create-menu\')')
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

    public function sort(bool | Closure $condition = true): static
    {
        $this->sort = $condition;

        return $this;
    }

    public function sortBy(string | Closure $sortBy = 'label'): static
    {
        if (! in_array($sortBy, ['label', 'navigation'])) {
            $sortBy = 'label';
        }

        $this->sortBy = $sortBy;

        return $this;
    }
}
