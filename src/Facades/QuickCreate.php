<?php

namespace FilamentQuickCreate\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getResourcesUsing(Closure $callback)
 * @method static bool sort(bool $sort)
 * @method static array getResources()
 * @method static bool sortingEnabled()
 *
 * @see \FilamentQuickCreate\QuickCreate
 */
class QuickCreate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return static::class;
    }
}
