<?php

namespace FilamentQuickCreate;

use Closure;
use Filament\Facades\Filament;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class QuickCreate
{
    use EvaluatesClosures;

    protected Closure $getResourcesUsing;

    protected array $excludes = [];

    protected array $includes = [];

    protected bool $sort = true;

    public function __construct()
    {
        $this->getResourcesUsing(fn () => Filament::getResources());
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

    public function sort(bool|Closure $condition = true): static
    {
        $this->sort = $condition;

        return $this;
    }

    public function getResourcesUsing(Closure $callback): static
    {
        $this->getResourcesUsing = $callback;

        return $this;
    }

    public function getExcludes(): array
    {
        return $this->evaluate($this->excludes);
    }

    public function getIncludes(): array
    {
        return $this->evaluate($this->includes);
    }

    public function isSortable(): bool
    {
        return $this->evaluate($this->sort);
    }

    public function getResources(): array
    {
        $resources = filled($this->getIncludes())
            ? $this->getIncludes()
            : $this->evaluate($this->getResourcesUsing);

        $list = collect($resources)
            ->filter(function($item) {
                return ! in_array($item, $this->getExcludes());
            })
            ->map(function($resourceName): ?array {
                $resource = app($resourceName);

                if ($resource->canCreate()) {
                    $actionName = 'create_'.Str::of($resource->getModel())->replace('\\', '')->snake();

                    return [
                        'resource_name' => $resourceName,
                        'label' => Str::ucfirst($resource->getModelLabel()),
                        'model' => $resource->getModel(),
                        'icon' => $resource->getNavigationIcon(),
                        'action_name' => $actionName,
                        'action' => ! $resource->hasPage('create') ? 'mountAction(\''.$actionName.'\')' : null,
                        'url' => $resource->hasPage('create') ? $resource::getUrl('create') : null,
                    ];
                }

                return null;
            })
            ->when($this->isSortable(), fn ($collection) => $collection->sortBy('label'))
            ->values()
            ->toArray();

        return array_filter($list);
    }
}
