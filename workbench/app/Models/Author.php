<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Workbench\Database\Factories\AuthorFactory;

class Author extends Model
{
    use HasFactory;

    protected static function newFactory(): AuthorFactory
    {
        return AuthorFactory::new();
    }
}
