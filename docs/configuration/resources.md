---
title: Resources
description: Choose which resources appear in the Quick Create menu and control their order.
---

# Resources

By default the menu lists every resource registered with the panel that the current user can create. These methods narrow and reorder that list.

## Excluding resources

`excludes()` removes resources from the list, leaving everything else:

```php
use Awcodes\QuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->excludes([
                    \App\Filament\Resources\UserResource::class,
                ]),
        ]);
}
```

This is the right choice when most resources belong in the menu and only a few do not.

## Including resources

`includes()` works the other way round — only the resources you name appear:

```php
use Awcodes\QuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->includes([
                    \App\Filament\Resources\UserResource::class,
                ]),
        ]);
}
```

Reach for this when a panel has thirty resources and only three or four are worth a shortcut.

> [!WARNING]
> Use one or the other, not both. When `includes()` is set it replaces the resource list entirely, and `excludes()` is then applied on top of it — so combining them can only ever subtract from what you included, which is rarely what anyone means.

Authorization still applies to both. A resource listed in `includes()` that the user cannot create will not appear.

## Sorting

Entries are sorted alphabetically by label. Pass `false` to keep the order they were registered with Filament instead:

```php
QuickCreatePlugin::make()
    ->sort(false)
```

## Sorting by navigation order

`sortBy('navigation')` orders the menu to match the panel's navigation, using each resource's navigation sort value:

```php
QuickCreatePlugin::make()
    ->sortBy('navigation')
```

Only `label` and `navigation` are accepted. Any other value falls back to `label` silently rather than erroring, so a typo here shows up as unchanged ordering rather than an exception.

`sortBy()` has no effect when sorting is off — `sort(false)` wins.
