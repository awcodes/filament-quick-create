<?php

declare(strict_types=1);

namespace Awcodes\QuickCreate\Tests\Fixtures\Resources\Authors\Pages;

use Awcodes\QuickCreate\Tests\Fixtures\Resources\Authors\AuthorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAuthors extends ManageRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
