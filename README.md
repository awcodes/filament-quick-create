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

### Registering keybindings

You can attach keyboard shortcuts to trigger the Quick Create dropdown. To configure these, pass the keyBindings() method to the configuration:

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->keyBindings(['command+shift+a', 'ctrl+shift+a']),
        ])
}
```

### Create Another

By default, the ability to create another record will respect the settings of your 'create record' or 'list records' create action. This can be overridden to either enable or disable it for all resources with the `createAnother()` method.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->createAnother(false),
        ])
}
```

### Appearance

#### Rounded

By default, the Quick Create button will be fully rounded if you would like to have a more square button you can disable the rounding with the `rounded()` method.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->rounded(false),
        ])
}
```

#### Hiding Icons

If you prefer to not show icons for the items in the menu you can disable them with the `hiddenIcons()` method.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->hiddenIcons(),
        ])
}
```

#### Setting a label

If you prefer to show a label with the plus icon you can set it using the `label()` method and passing your label to it.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->label('New'),
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

### Hiding Quick Create

By default, Quick Create is visible if there are registered resources. If you would like to hide it you may use the `hidden()` modifier to do so.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->hidden(fn() => Filament::getTenant()->requiresOnboarding()),
        ])
}
```

### Render Plugin on a Custom Panel Hook

By default, Quick Create plugin renders using `'panels::user-menu.before'` Filament Panel Render Hook. If you would like to customize this to render at a different render hook, you may use the `renderUsingHook(string $panelHook)` modifier to do so. You may read about the available Render Hooks in Filament PHP [here](https://filamentphp.com/docs/3.x/support/render-hooks#available-render-hooks)

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
use Filament\View\PanelsRenderHook;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->renderUsingHook(PanelsRenderHook::SIDEBAR_NAV_END),
        ])
}
```

### Forcing all resources to use modals

Quick create will automatically determine if it should redirect to a create page or to show the form in a modal based on the resource. If you prefer to force all items to be show in a modal you can do so with the `alwaysShowModal()` modifier.

```php
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->alwaysShowModal(),
        ])
}
```
