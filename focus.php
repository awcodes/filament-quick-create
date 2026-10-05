<?php

declare(strict_types=1);

use Awcodes\Focus\Card;
use Awcodes\Focus\Enums\Size;
use Awcodes\Focus\Screenshot;
use Awcodes\Focus\ScreenshotSuite;

/*
 * Documentation screenshots for Quick Create, generated with awcodes/focus from the Workbench (run `composer build`
 * first). The Workbench seeds fixed posts, and registers five resources with fixed labels and icons in two panels:
 * /admin keeps the plugin's defaults, and /app changes its appearance and modal options.
 */

// The dropdown is teleported to <body>, so its panel is found by the documented per-entry class it contains.
$menu = '[x-ref="panel"]:has(.quick-create-action-post)';
$button = 'button[aria-label="Quick Create"]';

// The awcodes card templates frame each screenshot at 1400x816. The posts page is captured at 3/4 of that size so
// the open menu stays legible once the template scales it up.
$card = [1050, 612];

return ScreenshotSuite::make()
    ->screenshots([
        // The open menu with its trigger button above it.
        Screenshot::make('menu')
            ->visit('/admin')
            ->click($button)
            ->waitFor($menu)
            ->focus($menu)
            ->padding(64),

        // Posts have no create page, so choosing the entry opens its form in a modal.
        Screenshot::make('modal')
            ->visit('/admin/posts')
            ->click($button)
            ->waitFor($menu)
            ->click('.quick-create-action-post')
            ->waitFor('[data-focus="quick-create-modal"]:visible')
            // The package leaves the menu open behind the modal; hide it so the backdrop shows only the page.
            ->hide($menu)
            ->focus('[data-focus="quick-create-modal"]:visible'),

        // The /app panel: rounded(false), label('New'), and hiddenIcons().
        Screenshot::make('label')
            ->visit('/app')
            ->click($button)
            ->waitFor($menu)
            ->focus($menu)
            ->padding(64),

        // The /app panel's tooltip('Create something new').
        Screenshot::make('tooltip')
            ->visit('/app')
            ->hover($button)
            ->waitFor('.tippy-box')
            ->focus($button)
            ->minSize(480, 200),

        // The /app panel: slideOver() with a custom modalHeading() and modalDescription().
        Screenshot::make('slide-over')
            ->visit('/app/posts')
            ->click($button)
            ->waitFor($menu)
            ->click('.quick-create-action-post')
            ->waitFor('[data-focus="quick-create-slide-over"]:visible')
            ->viewport(),

        // The share-image source, shaped to the card templates' screenshot slots. The two-up templates show it
        // dark in slot 1 and light in slot 2, so it is captured in both themes.
        Screenshot::make('card-menu')
            ->viewportSize(...$card)
            ->visit('/admin/posts')
            ->click($button)
            ->waitFor($menu)
            // The open menu overlaps the page's own "New post" button.
            ->hide('[data-focus="posts-create-action"]')
            ->viewport(),
    ])
    ->cardTemplates('https://github.com/awcodes/focus-templates/tree/v2.0.0/dist')
    ->cards([
        // Open Graph and the GitHub social preview share one 2400x1260 template; GitHub crops 30px top and bottom.
        Card::make('social')
            ->template('two-up-wide')
            ->title('Quick Create')
            ->screenshots(['card-menu', 'card-menu'])
            ->sizes([Size::OpenGraph, Size::GitHubSocial]),

        // The Filament plugin directory's 2560x1440 thumbnail.
        Card::make('thumbnail')
            ->template('two-up')
            ->title('Quick Create')
            ->screenshots(['card-menu', 'card-menu'])
            ->sizes([Size::Filament]),
    ]);
