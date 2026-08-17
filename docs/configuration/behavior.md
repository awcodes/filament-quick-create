---
title: Behavior
description: Add keyboard shortcuts, move the button to another render hook, or hide it entirely.
---

# Behavior

## Keyboard shortcuts

`keyBindings()` opens the dropdown from the keyboard. Pass one binding or several:

```php
use Awcodes\QuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->keyBindings(['command+shift+a', 'ctrl+shift+a']),
        ]);
}
```

Bindings are written as `modifier+key`, and every one in the array triggers the same dropdown — the pair above is the usual way to cover macOS and Windows or Linux with one configuration.

Shortcuts are registered globally on the page, so they fire wherever focus is. Choose combinations that the browser and the operating system do not already claim.

## Moving the button

Quick Create renders through Filament's render hook system, at `panels::user-menu.before` by default. `renderUsingHook()` moves it anywhere else a panel render hook exists:

```php
use Awcodes\QuickCreate\QuickCreatePlugin;
use Filament\View\PanelsRenderHook;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->renderUsingHook(PanelsRenderHook::SIDEBAR_NAV_END),
        ]);
}
```

Constants on `Filament\View\PanelsRenderHook` are the safest way to name a hook; the available hooks are listed in Filament's own render hooks documentation.

## Hiding the menu

The button appears whenever there is at least one resource to show. `hidden()` suppresses it regardless:

```php
QuickCreatePlugin::make()
    ->hidden()
```

A closure makes it conditional, which is the more useful form — hiding the shortcut during onboarding, for instance, or from users part-way through a setup flow:

```php
QuickCreatePlugin::make()
    ->hidden(fn () => Filament::getTenant()->requiresOnboarding())
```

This hides the button only. It is a presentation choice, not an authorization boundary — creating records is still governed by each resource's own policy, which Quick Create already respects when building the menu.
