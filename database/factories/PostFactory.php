<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        $slug = Str::of($title)->lower()->kebab()->toString();

        return [
            'title' => $title,
            'slug' => $slug,
            'body' => implode(' ', fake()->paragraphs(3)),
            'user_id' => User::factory(),
        ];
    }
}
