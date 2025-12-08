<?php

namespace Tests\Http\Posts;

use App\Http\Controllers\PostController;
use App\Models\Channel;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    protected function action(): string
    {
        return action([PostController::class, 'store']);
    }

    public function test_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $channel = Channel::create(['name' => 'General', 'slug' => 'general']);

        Sanctum::actingAs($user);

        $response = $this->postJson($this->action(), [
            'title' => 'My First Post',
            'body' => 'This is the body of my first post.',
            'channel' => $channel->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'title', 'slug', 'body', 'createdAt', 'updatedAt',
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'My First Post',
            'slug' => 'my-first-post',
            'user_id' => $user->id,
            'channel_id' => $channel->id,
        ]);
    }

    public function test_guest_cannot_create_post(): void
    {
        $this->postJson($this->action(), [
            'title' => 'My First Post',
            'body' => 'Body',
            'channel' => 1,
        ])->assertUnauthorized();
    }

    #[DataProvider('invalidData')]
    public function test_validation_rules(array $data, array $errors): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson($this->action(), $data)
            ->assertJsonValidationErrors($errors);
    }

    public static function invalidData(): array
    {
        return [
            'missing title' => [
                ['body' => 'Body', 'channel' => 1],
                ['title'],
            ],
            'missing body' => [
                ['title' => 'Title', 'channel' => 1],
                ['body'],
            ],
            'missing channel' => [
                ['title' => 'Title', 'body' => 'Body'],
                ['channel'],
            ],
            'invalid channel' => [
                ['title' => 'Title', 'body' => 'Body', 'channel' => 999],
                ['channel'],
            ],
            'title too long' => [
                ['title' => str_repeat('a', 257), 'body' => 'Body', 'channel' => 1],
                ['title'],
            ],
        ];
    }

    public function test_slug_generation_handles_duplicates(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $channel = Channel::create(['name' => 'General', 'slug' => 'general']);

        Post::create([
            'title' => 'My Post',
            'slug' => 'my-post',
            'body' => 'Body',
            'channel_id' => $channel->id,
            'user_id' => $user->id,
        ]);

        $response = $this->postJson($this->action(), [
            'title' => 'My Post',
            'body' => 'Body',
            'channel' => $channel->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('posts', [
            'title' => 'My Post',
            'slug' => 'my-post-2',
        ]);
    }
}
