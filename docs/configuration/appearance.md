---
title: Appearance
description: Change the Quick Create button's shape, label, and tooltip, and hide the menu's icons.
---

# Appearance

## Button shape

The button is fully rounded by default. Pass `false` for a squarer button with rounded corners:

```php
use Awcodes\QuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->rounded(false),
        ]);
}
```

## Adding a label

By default the button is an icon on its own. `label()` adds text beside the plus icon, and widens the button to suit:

```php
QuickCreatePlugin::make()
    ->label('New')
```

## Tooltip

`tooltip()` attaches a tooltip to the button. Called with no argument it uses the package's own translated string, "Quick Create":

```php
QuickCreatePlugin::make()
    ->tooltip()
```

Pass a string for your own wording:

```php
QuickCreatePlugin::make()
    ->tooltip('Create something new')
```

There is no tooltip unless you ask for one. Note that the button always carries an accessible name regardless — the tooltip is a visual affordance, not the label a screen reader announces.

## Hiding menu icons

Each entry in the dropdown shows its resource's navigation icon. `hiddenIcons()` renders labels alone:

```php
QuickCreatePlugin::make()
    ->hiddenIcons()
```

This is worth considering when your resources share an icon, or use none, in which case the column of identical glyphs adds nothing.

## Styling individual entries

Each dropdown item carries a class derived from its label — a resource labelled "Blog post" renders with `quick-create-action-blog-post`. That gives you a hook for targeting one entry from your theme without overriding the package's views.
