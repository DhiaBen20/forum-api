<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'body' => fake()->sentence(),
            'user_id' => User::factory(),
        ];
    }

    public function comment(): static
    {
        return $this->state(fn (array $attributes) => [
            'post_id' => Post::factory(),
        ]);
    }

    public function reply(): static
    {
        return $this->state(fn (array $attributes) => [
            'comment_id' => Comment::factory()->comment(),
        ]);
    }
}
