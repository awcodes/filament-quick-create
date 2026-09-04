<?php

declare(strict_types=1);

use Awcodes\QuickCreate\Components\QuickCreateMenu;
use Awcodes\QuickCreate\QuickCreatePlugin;
use Filament\Facades\Filament;
use Workbench\App\Filament\Resources\Authors\AuthorResource;
use Workbench\App\Filament\Resources\Users\UserResource;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->panel = Filament::getCurrentOrDefaultPanel();
});

it('displays the sidebar view', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make(),
        ]);

    $this->get('/admin')
        ->assertOk()
        ->assertSee('quick-create-component');
});

it('shows multiple resources with icons in the quick create menu', function () {
    $this->get('/admin')->assertOk();

    livewire(QuickCreateMenu::class)
        ->assertViewHas('resources', function (array $resources): bool {
            $resources = collect($resources);

            return $resources->pluck('label')->all() === ['Author', 'Category', 'User']
                && $resources->every(fn (array $resource): bool => filled($resource['icon']));
        });
});

it('excludes resources', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->excludes([
                    UserResource::class,
                ]),
        ]);

    $this->get('/admin')
        ->assertOk();

    livewire(QuickCreateMenu::class)
        ->assertViewHas('resources', function ($resources) {
            return (! in_array(UserResource::class, $resources))
                && $resources[0]['label'] === 'Author';
        })
        ->assertSee('quick-create-action-author')
        ->assertDontSee('quick-create-action-user');
});

it('includes resources', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->includes([
                    UserResource::class,
                ]),
        ]);

    $this->get('/admin')
        ->assertOk();

    livewire(QuickCreateMenu::class)
        ->assertViewHas('resources', function ($resources) {
            return (! in_array(AuthorResource::class, $resources))
                && $resources[0]['label'] === 'User';
        })
        ->assertSee('quick-create-action-user')
        ->assertDontSee('quick-create-action-author');
});

it('has correct settings', function () {
    $this->panel
        ->plugins([
            QuickCreatePlugin::make()
                ->rounded(false)
                ->hiddenIcons()
                ->label('test')
                ->keyBindings([
                    'ctrl+shift+a',
                ]),
        ]);

    $this->get('/admin')
        ->assertOk();

    livewire(QuickCreateMenu::class)
        ->assertSet('rounded', false)
        ->assertSet('hiddenIcons', true)
        ->assertSet('label', 'test')
        ->assertSet('keyBindings', ['ctrl+shift+a']);
});
