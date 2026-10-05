<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Resources\Posts\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Workbench\App\Filament\Resources\Posts\PostResource;

class ManagePosts extends ManageRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // A hook so Focus can hide the page's own create button where the open menu would overlap it.
            CreateAction::make()
                ->extraAttributes(['data-focus' => 'posts-create-action']),
        ];
    }
}
