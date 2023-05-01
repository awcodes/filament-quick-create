<?php

namespace FilamentQuickCreate;

use Filament\Context;
use Filament\Contracts\Plugin;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class QuickCreatePlugin implements Plugin
{

    public function getId(): string
    {
        return 'filament-quick-create';
    }

    public function register(Context $context): void
    {
        //
    }

    public function boot(Context $context): void
    {
        Livewire::component('quick-create-menu', Http\Livewire\QuickCreateMenu::class);

        $context
            ->renderHook(
                name: 'user-menu.start',
                callback: fn (): string => Blade::render('@livewire(\'quick-create-menu\')')
            );
    }
}