<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/workbench/theme.css', fn () => response()->file(
    __DIR__ . '/../public/css/filament/admin/theme.css',
    ['Content-Type' => 'text/css'],
))->name('workbench.theme');
