---
title: Modals
description: Control how Quick Create renders create forms — slide-overs, widths, headings, and creating another.
---

# Modals

When Quick Create opens a form inline rather than navigating to a create page, these methods shape the modal it uses.

## Forcing every resource into a modal

Quick Create decides per resource whether to open a modal or navigate to that resource's create page. `alwaysShowModal()` overrides the decision so nothing ever leaves the current page:

```php
use Awcodes\QuickCreate\QuickCreatePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QuickCreatePlugin::make()
                ->alwaysShowModal(),
        ]);
}
```

The form is built from the resource's own schema, so a resource with a create page and one without both render the same fields.

## Slide-overs

`slideOver()` renders the form in a slide-over panel instead of a centred modal:

```php
QuickCreatePlugin::make()
    ->slideOver()
```

## Modal width

`modalWidths()` sets the width. A single value applies to every resource:

```php
QuickCreatePlugin::make()
    ->modalWidths('2xl')
```

An array sets widths per entry, matched by the entry's **position in the menu** rather than by resource class:

```php
QuickCreatePlugin::make()
    ->modalWidths(['2xl', 'lg', 'sm'])
```

> [!CAUTION]
> Because the array is positional, its meaning depends on the menu's order — and that order is itself affected by sorting, authorization, and includes or excludes. An entry hidden from one user shifts every width after it. Prefer a single value unless the menu's contents are fixed.

An entry with no matching index falls back to the first width in the array.

## Modal heading and description

`modalHeading()` and `modalDescription()` replace the modal's default text. Both accept a `:label` placeholder, substituted with the resource's model label:

```php
QuickCreatePlugin::make()
    ->modalHeading('New :label')
    ->modalDescription('Fill in the details for this :label.')
```

With a resource whose model label is "Blog post", that heading renders as "New Blog post".

Leave either unset to keep Filament's default.

## Extra modal attributes

`modalExtraAttributes()` puts arbitrary attributes on the modal window, for hooks your own CSS or JavaScript needs:

```php
QuickCreatePlugin::make()
    ->modalExtraAttributes(['class' => 'quick-create-modal'])
```

## Create another

Filament's create forms can offer a "create another" option. By default Quick Create inherits whatever the resource already does — it reads the setting from the resource's create page, or from the create action on its list page when there is no create page.

Override it for every resource with `createAnother()`:

```php
QuickCreatePlugin::make()
    ->createAnother(false)
```

Passing `true` enables it everywhere, `false` disables it everywhere. Leave the method off entirely to keep each resource's own behaviour, which is usually what you want.
