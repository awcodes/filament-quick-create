<?php

declare(strict_types=1);

use Awcodes\QuickCreate\QuickCreatePlugin;
use Awcodes\QuickCreate\Tests\Fixtures\Resources\Users\UserResource;
use Filament\Facades\Filament;
use Filament\View\PanelsRenderHook;

beforeEach(function () {
    $this->panel = Filament::getCurrentOrDefaultPanel();
});

it('can register the plugin', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make(),
        ]);

    expect(Filament::getPlugin('quick-create'))->toBeInstanceOf(QuickCreatePlugin::class);
});

it('can set excluded resources', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->excludes([
                    UserResource::class,
                ]),
        ]);

    expect(Filament::getPlugin('quick-create')->getExcludes())
        ->toContain(UserResource::class);
});

it('can set included resources', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->includes([
                    UserResource::class,
                ]),
        ]);

    expect(Filament::getPlugin('quick-create')->getIncludes())
        ->toContain(UserResource::class);
});

it('can set disabled sort', function (bool|Closure $condition) {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->sort($condition),
        ]);

    expect(Filament::getPlugin('quick-create')->isSortable())->toBeFalse();
})->with([
    false,
    fn () => false,
]);

it('can set sorting by navigation', function (string|Closure $field) {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->sortBy($field),
        ]);

    expect(Filament::getPlugin('quick-create')->getSortField())->toBe('navigation');
})->with([
    'navigation',
    fn () => 'navigation',
]);

it('can set registered key bindings', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->keyBindings([
                    'ctrl+shift+a',
                    'command+shift+a',
                ]),
        ]);

    expect(Filament::getPlugin('quick-create')->getKeyBindings())
        ->toBe(['ctrl+shift+a', 'command+shift+a']);
});

it('can set disabling create another', function (bool|Closure $condition) {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->createAnother($condition),
        ]);

    expect(Filament::getPlugin('quick-create')->canCreateAnother())->toBeFalse();
})->with([
    false,
    fn () => false,
]);

it('can set disabling rounded', function (bool|Closure $condition) {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->rounded($condition),
        ]);

    expect(Filament::getPlugin('quick-create')->isRounded())->toBeFalse();
})->with([
    false,
    fn () => false,
]);

it('can set a label', function (string|Closure $label) {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->label($label),
        ]);

    expect(Filament::getPlugin('quick-create')->getLabel())->toBe('New');
})->with([
    'New',
    fn () => 'New',
]);

it('can set slide overs', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->slideOver(),
        ]);

    expect(Filament::getPlugin('quick-create')->shouldUseSlideOver())->toBeTrue();
});

it('can set being hidden', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->hidden(fn () => true),
        ]);

    expect(Filament::getPlugin('quick-create')->shouldBeHidden())->toBeTrue();
});

it('can set being registered with a custom render hook', function (string|Closure|PanelsRenderHook $hook) {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->renderUsingHook($hook),
        ]);

    expect(Filament::getPlugin('quick-create')->getRenderHook())->toBe($hook);
})->with([
    'panels::sidebar.nav.end',
    PanelsRenderHook::SIDEBAR_NAV_END,
    fn () => PanelsRenderHook::SIDEBAR_NAV_END,
]);

it('can set being always shown in modals', function (bool|Closure $condition) {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->alwaysShowModal($condition),
        ]);

    expect(Filament::getPlugin('quick-create')->shouldUseModal())->toBeTrue();
})->with([
    true,
    fn () => true,
]);
