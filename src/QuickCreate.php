<?php

namespace FilamentQuickCreate;

use Closure;
use Filament\Facades\Filament;

class QuickCreate {

    public Closure $getResourcesUsing;

    public function getResourcesUsing(Closure $callback):static{
        $this->getResourcesUsing= $callback;
        return $this;
    }

    public function __construct()
    {
        $this->getResourcesUsing(fn()=>Filament::getResources());
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
    
    public function getResources(){
        return $this->evaluate($this->getResourcesUsing);
    }
}