---
title: Appearance
description: Change the Quick Create button's shape, style, label, and tooltip, and hide the menu's icons.
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

## Icon button

`iconButton()` drops the button's filled background and renders the trigger as a plain Filament icon button. It's the same style as the notifications bell in the top bar, so it sits in line with the panel's other top-bar icons:

```php
QuickCreatePlugin::make()
    ->iconButton()
```

![The Quick Create trigger as a plain grey plus icon in the panel's top bar, with no background, and its dropdown open beneath it](assets/icon-button-light.png#gh-light-mode-only)
![The Quick Create trigger as a plain grey plus icon in the panel's top bar, with no background, and its dropdown open beneath it](assets/icon-button-dark.png#gh-dark-mode-only)

An icon button has no room for text, so `iconButton()` has no effect when `label()` is set. `rounded()` doesn't apply either, because Filament's icon button has its own shape. `tooltip()` and `keyBindings()` work as usual.

## Adding a label

By default the button is an icon on its own. `label()` adds text beside the plus icon, and widens the button to suit:

```php
QuickCreatePlugin::make()
    ->label('New')
```

![A square Quick Create button labelled New, configured with rounded(false), label('New'), and hiddenIcons(), with its dropdown open listing resources without icons](assets/label-light.png#gh-light-mode-only)
![A square Quick Create button labelled New, configured with rounded(false), label('New'), and hiddenIcons(), with its dropdown open listing resources without icons](assets/label-dark.png#gh-dark-mode-only)

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

![The Quick Create button with a Create something new tooltip shown beneath it](assets/tooltip-light.png#gh-light-mode-only)
![The Quick Create button with a Create something new tooltip shown beneath it](assets/tooltip-dark.png#gh-dark-mode-only)

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
