<?php

declare(strict_types=1);

namespace Awcodes\QuickCreate\Tests\Fixtures\Models;

use Awcodes\QuickCreate\Tests\Database\Factories\AuthorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected static function newFactory(): AuthorFactory
    {
        return AuthorFactory::new();
    }
}
