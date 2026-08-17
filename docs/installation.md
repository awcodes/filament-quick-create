---
title: Installation
description: Install Quick Create, register it with a panel, and add its views to your theme.
---

# Installation

## Requirements

- PHP 8.2 or higher
- Filament 4.x or 5.x

Earlier releases of this package support earlier versions of Filament:

| Package Version | Filament Version |
| --- | --- |
| 2.x | 2.x |
| 3.x | 3.x |
| 4.x | 4.x |
| 5.x | 4.x & 5.x |

## Install the package

Install with Composer:

```bash
composer require awcodes/filament-quick-create
```

The service provider is registered automatically, and there is no configuration file to publish.

## Register the plugin

Add the plugin to the panel you want the button to appear in:

```php
use Awcodes\QuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make(),
        ]);
}
```

That is all that is required. With no further configuration the menu lists every resource in the panel that the current user is allowed to create.

Register it on each panel that should have the button — plugins are per-panel, and the settings on one do not carry to another.

## Register the views with Tailwind

The menu is rendered from Blade views in the package, so your Tailwind build has to be able to see them. Add the package as a source in your theme's CSS file:

```css
@source '../../../../vendor/awcodes/filament-quick-create/resources/**/*.blade.php';
```

> [!IMPORTANT]
> This step needs a custom theme. If you have not created one yet, follow [Creating a custom theme](https://filamentphp.com/docs/4.x/styling/overview#creating-a-custom-theme) in the Filament documentation first.

The relative path above assumes the conventional theme location, `resources/css/filament/<panel>/theme.css`. Adjust the number of `../` segments if your theme lives elsewhere — the path has to resolve to `vendor/awcodes/filament-quick-create/resources` from the file it is written in.

Without this step the button still renders, but unstyled.

## Next steps

The menu works as-is. To change what it lists, see [Resources](configuration/resources.md); for everything else, start from the [index](index.md).
