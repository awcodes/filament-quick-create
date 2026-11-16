<?php

declare(strict_types=1);

namespace Workbench\App\Providers\Filament;

use Awcodes\QuickCreate\QuickCreatePlugin;
use Exception;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Theme;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Workbench\App\Filament\Pages\Auth\Login;
use Workbench\App\Filament\Resources\Authors\AuthorResource;
use Workbench\App\Filament\Resources\Categories\CategoryResource;
use Workbench\App\Filament\Resources\Posts\PostResource;
use Workbench\App\Filament\Resources\Tags\TagResource;
use Workbench\App\Filament\Resources\Users\UserResource;

/**
 * A second panel with the plugin's appearance and modal options changed from their defaults, so both can be
 * inspected side by side with the admin panel.
 */
class AppPanelProvider extends PanelProvider
{
    /** @throws Exception */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->login(Login::class)
            ->brandName('Quick Create')
            ->plugin(
                QuickCreatePlugin::make()
                    ->label('New')
                    ->rounded(false)
                    ->tooltip('Create something new')
                    ->hiddenIcons()
                    ->slideOver()
                    ->modalHeading('New :label')
                    ->modalDescription('Fill in the details for this :label.')
                    ->modalExtraAttributes(['data-focus' => 'quick-create-slide-over']),
            )
            ->theme(Theme::make('workbench')->html(
                fn (): string => route('workbench.theme'),
            ))
            ->resources([
                UserResource::class,
                AuthorResource::class,
                CategoryResource::class,
                PostResource::class,
                TagResource::class,
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                $this->csrfMiddleware(),
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    /**
     * @return class-string
     */
    private function csrfMiddleware(): string
    {
        // PreventRequestForgery replaced VerifyCsrfToken in Laravel 13. Laravel 12 rows
        // in the CI matrix don't have it yet, so fall back there, as Filament's own panel
        // generator does.
        return class_exists(PreventRequestForgery::class)
            ? PreventRequestForgery::class
            : VerifyCsrfToken::class;
    }
}
