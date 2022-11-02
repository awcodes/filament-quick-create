<?php

namespace FilamentQuickCreate;

use Closure;
use Filament\Facades\Filament;

class QuickCreate
{
    public Closure $getResourcesUsing;

    public bool $sort = true;

    public function __construct()
    {
        $this->getResourcesUsing(fn () => Filament::getResources());
    }

    public function sort(bool $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    public function sortingEnabled(): bool
    {
        return config('filament-quick-create.sort', $this->sort);
    }

    public function getResourcesUsing(Closure $callback): static
    {
        $this->getResourcesUsing = $callback;

        return $this;
    }

    public function evaluate($value, array $parameters = [])
    {
        if ($value instanceof Closure) {
            return app()->call(
                $value,
                $parameters
            );
        }

        return $value;
    }

    public function getResources(): array
    {
        return $this->evaluate($this->getResourcesUsing);
    }
}
