<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Workbench\App\Models\Author;
use Workbench\App\Models\Category;
use Workbench\App\Models\Post;
use Workbench\App\Models\Tag;
use Workbench\Database\Factories\UserFactory;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        UserFactory::new()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => '2026-01-01 09:00:00',
            'remember_token' => null,
            'created_at' => '2026-01-01 09:00:00',
            'updated_at' => '2026-01-01 09:00:00',
        ]);

        foreach ([
            ['name' => 'Ada Lovelace', 'username' => 'ada'],
            ['name' => 'Grace Hopper', 'username' => 'grace'],
            ['name' => 'Alan Turing', 'username' => 'alan'],
        ] as $author) {
            Author::query()->forceCreate([...$author, 'created_at' => '2026-01-01 09:00:00', 'updated_at' => '2026-01-01 09:00:00']);
        }

        foreach ([
            ['name' => 'Announcements', 'color' => '#f59e0b'],
            ['name' => 'Guides', 'color' => '#10b981'],
            ['name' => 'Releases', 'color' => '#6366f1'],
        ] as $category) {
            Category::query()->create([...$category, 'created_at' => '2026-01-01 09:00:00', 'updated_at' => '2026-01-01 09:00:00']);
        }

        foreach ([
            ['title' => 'Introducing Quick Create', 'slug' => 'introducing-quick-create', 'published_at' => '2026-03-02'],
            ['title' => 'Creating records without leaving the page', 'slug' => 'creating-records-without-leaving-the-page', 'published_at' => '2026-03-16'],
            ['title' => 'Choosing which resources appear', 'slug' => 'choosing-which-resources-appear', 'published_at' => '2026-04-06'],
            ['title' => 'Slide-overs, widths, and headings', 'slug' => 'slide-overs-widths-and-headings', 'published_at' => '2026-04-20'],
            ['title' => 'Keyboard shortcuts for the menu', 'slug' => 'keyboard-shortcuts-for-the-menu', 'published_at' => '2026-05-11'],
        ] as $post) {
            Post::query()->create([...$post, 'created_at' => '2026-01-01 09:00:00', 'updated_at' => '2026-01-01 09:00:00']);
        }

        foreach ([
            ['name' => 'Filament', 'slug' => 'filament'],
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'Livewire', 'slug' => 'livewire'],
        ] as $tag) {
            Tag::query()->create([...$tag, 'created_at' => '2026-01-01 09:00:00', 'updated_at' => '2026-01-01 09:00:00']);
        }
    }
}
