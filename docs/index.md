---
title: Quick Create
description: Add a dropdown to a Filament panel's header for creating records from anywhere in the app.
---

# Quick Create

Quick Create adds a plus button to a [Filament Panel](https://filamentphp.com), with a dropdown listing the things a user can create. Wherever they are in the panel, a new record is two clicks away — no navigating to the right resource first.

## What appears in the menu

Every resource registered with the current panel, filtered by two rules:

1. **Authorization.** Each resource's `canCreate()` is checked, so a user who cannot create a record never sees it offered. This is Filament's own authorization — there is nothing extra to configure.
2. **Your own list.** You can narrow the set further with [excludes or includes](configuration/resources.md).

Entries are labelled with the resource's model label and carry its navigation icon.

> [!NOTE]
> When tenancy is enabled and there is no current tenant, the menu lists nothing. It fills in once a tenant is set.

## Modal or page

Choosing an entry does one of two things, decided per resource:

- A resource **with** a `create` page navigates to it, the same as clicking through the navigation would.
- A resource **without** one opens a create form in a modal, built from the resource's own form schema.

That default suits most panels — heavier resources keep their dedicated page, simple ones stay inline. You can [force every resource into a modal](configuration/modals.md) if you would rather never leave the current page.

## What you can configure

Everything is set with chained methods on the plugin:

- **[Resources](configuration/resources.md)** — which resources appear, and in what order.
- **[Appearance](configuration/appearance.md)** — button shape, label, tooltip, and menu icons.
- **[Modals](configuration/modals.md)** — slide-overs, widths, headings, and "create another".
- **[Behavior](configuration/behavior.md)** — keyboard shortcuts, render position, and hiding the menu.

## Next steps

Start with [Installation](installation.md).
