<?php

declare(strict_types=1);

namespace Awcodes\QuickCreate\Tests\Fixtures\Resources\Users\Pages;

use Awcodes\QuickCreate\Tests\Fixtures\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
