<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Author;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'twitter' => fake()->optional()->userName(),
            'bio' => fake()->optional()->paragraph(),
        ];
    }
}
