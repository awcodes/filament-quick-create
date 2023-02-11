<?php

namespace FilamentQuickCreate\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * @method static excludes(array $resources)
 * @method static includes(array $resources)
 * @method static sort(bool $sort)
 * @method static getResourcesUsing(Closure $callback)
 * @method static array getExcludes()
 * @method static array getIncludes()
 * @method static bool isSortable()
 * @method static array getResources()
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
