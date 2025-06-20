<?php

declare(strict_types=1);

namespace Awcodes\QuickCreate\Tests\Fixtures\Resources\Users\Pages;

use Awcodes\QuickCreate\Tests\Fixtures\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
