<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Resources\Categories\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Workbench\App\Filament\Resources\Categories\CategoryResource;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
