<?php

namespace FilamentQuickCreate\Facades;

use Illuminate\Support\Facades\Facade;



class QuickCreate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return static::class;
    }
}
