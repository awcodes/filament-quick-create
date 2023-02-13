# Quick Create for Filament

Plugin for [Filament Admin Panel](https://filamentphp.com) that adds a dropdown menu to the header to quickly create new items from anywhere in your app.

![screen-shot](https://user-images.githubusercontent.com/3596800/218512680-6515eddb-cddf-49b3-a4f1-af549b7f6cb6.jpg)

## Installation

Install the package via composer

```bash
composer require awcodes/filament-quick-create
```

## Upgrading from 1.x

1. If applicable, move excludes from `config\filament-quick-create.php` to use the new [excludes() method](#excluding-resources).
2. If applicable, move your sort option from `config\filament-quick-create.php` to use the [sort() method](#sorting).
3. Delete `config\filament-quick-create.php`.

***The `getResourcesUsing()` method can still be used, but it's recommended to use the new [includes() method](#including-resources) instead.***

## Usage

By default, Quick Create will use all resources that are registered with Filament. That means you can simply install the plugin, and it will just work. All resources will still follow the authorization used by Filament, meaning that if a user doesn't have permission to create a record it will not be listed in the dropdown.

However, there are use cases where you need to override this functionality. You can achieve this with the `QuickCreate` facade in a service provider's `boot()` method inside of `Filament::serving()`.

> **Warning**
> Excludes and includes are not meant to work together. You should use one or the other, but not both.

### Excluding Resources

Excluding resources will filter them out of the registered resources to prevent them from displaying in the dropdown.

```php
use FilamentQuickCreate\Facades\QuickCreate;

Filament::serving(function() {
    QuickCreate::excludes([
        \App\Filament\Resources\SeoResource::class
        \App\Filament\Resources\UserResource::class
    ]);
});
```

### Including Resources

Sometimes, it might be easier to only include some resources instead of filtering them out. For instance, you have 30 resources but only want to display 3 to 4 in the dropdown.

```php
use FilamentQuickCreate\Facades\QuickCreate;

Filament::serving(function() {
    QuickCreate::includes([
        \App\Filament\Resources\SeoResource::class
        \App\Filament\Resources\UserResource::class
    ]);
});
```

### Sorting

By default, Quick Create will sort all the displayed options in descending order. This can be disabled should you choose. In which case they will be displayed in the order they are registered with Filament.

```php
use FilamentQuickCreate\Facades\QuickCreate;

Filament::serving(function(){
    QuickCreate::sort(bool|Closure);
});
```
