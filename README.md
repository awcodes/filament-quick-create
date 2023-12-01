# Quick Create for Filament

Plugin for [Filament Admin Panel](https://filamentphp.com) that adds a dropdown menu to the header to quickly create new items from anywhere in your app.

![quick-create-og](https://res.cloudinary.com/aw-codes/image/upload/w_1200,f_auto,q_auto/plugins/quick-create/awcodes-quick-create.jpg)

## Installation

Install the package via composer

```bash
composer require awcodes/filament-quick-create
```

In an effort to align with Filament's theming methodology you will need to use a custom theme to use this plugin.

> **Note**
> If you have not set up a custom theme and are using a Panel follow the instructions in the [Filament Docs](https://filamentphp.com/docs/3.x/panels/themes#creating-a-custom-theme) first. The following applies to both the Panels Package and the standalone Forms package.

Add the plugin's views to your `tailwind.config.js` file.

```js
content: [
    '<path-to-vendor>/awcodes/filament-quick-create/resources/**/*.blade.php',
]
```

## Usage

By default, Quick Create will use all resources that are registered with current Filament context. All resources will follow the authorization used by Filament, meaning that if a user doesn't have permission to create a record it will not be listed in the dropdown.

### Registering the plugin

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make(),
        ])
}
```

> **Warning**
> Excludes and includes are not meant to work together. You should use one or the other, but not both.

### Excluding Resources

Excluding resources will filter them out of the registered resources to prevent them from displaying in the dropdown.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->excludes([
                    \App\Filament\Resources\UserResource::class,
                ]),
        ])
}
```

### Including Resources

Sometimes, it might be easier to only include some resources instead of filtering them out. For instance, you have 30 resources but only want to display 3 to 4 in the dropdown.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->includes([
                    \App\Filament\Resources\UserResource::class,
                ]),
        ])
}
```

### Sorting

By default, Quick Create will sort all the displayed options in descending order by Label. This can be disabled should you choose. In which case they will be displayed in the order they are registered with Filament.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->sort(false),
        ])
}
```

### Sorting by resource navigation

By default, Quick Create will sort all the displayed options by Label. This can be changed to resource navigation sort should you choose. In which case they will be displayed in the order they are displayed in the navigation.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->sortBy('navigation'),
        ])
}
```

### Slide Overs

By default, Quick Create will render simple resources in a standard modal. If you would like to render them in a slide over instead you may use the `slideOver()` modifier to do so.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->slideOver(),
        ])
}
```
