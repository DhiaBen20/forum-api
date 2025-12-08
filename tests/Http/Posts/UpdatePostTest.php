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

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;

    protected function action(Post $post): string
    {
        return action([PostController::class, 'update'], ['post' => $post]);
    }

    public function test_user_can_update_own_post(): void
    {
        $post = Post::factory()->create();

        Sanctum::actingAs($post->user);

        $response = $this->patchJson($this->action($post), [
            'title' => 'Updated Title',
            'body' => 'Updated Body',
            'channel' => $post->channel_id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
                'body' => 'Updated Body',
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'body' => 'Updated Body',
        ]);
    }

    public function test_user_cannot_update_other_users_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $this->patchJson($this->action($post), [
            'title' => 'Updated Title',
            'body' => 'Updated Body',
            'channel' => $post->channel_id,
        ])->assertForbidden();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'title' => 'Original Title',
        ]);
    }

    public function test_guest_cannot_update_post(): void
    {
        $post = Post::factory()->create();

        $this->patchJson($this->action($post), [])->assertUnauthorized();
    }

    #[DataProvider('invalidData')]
    public function test_validation_rules(array $data, array $errors): void
    {
        $user = User::factory()->create();
        $channel = Channel::create(['name' => 'General', 'slug' => 'general']);
        $post = Post::create([
            'title' => 'Original Title',
            'slug' => 'original-title',
            'body' => 'Original Body',
            'channel_id' => $channel->id,
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this->patchJson($this->action($post), $data)
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
}
