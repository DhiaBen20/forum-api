<?php

namespace Tests\Http\Likes;

use App\Http\Controllers\LikeController;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    protected function action(string $type, int $id): string
    {
        return action([LikeController::class, 'store'], ['type' => $type, 'likeable' => $id]);
    }

    public function test_authenticated_user_can_like_a_post()
    {
        $user = $this->sanctumSignIn();
        $post = Post::factory()->create();

        $response = $this->postJson($this->action('posts', $post->id));

        $response->assertStatus(204);
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => Post::class,
        ]);
    }

    public function test_authenticated_user_can_unlike_a_post()
    {
        $user = $this->sanctumSignIn();
        $post = Post::factory()->create();
        $post->likes()->create(['user_id' => $user->id]);

        $response = $this->deleteJson($this->action('posts', $post->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => Post::class,
        ]);
    }

    public function test_authenticated_user_can_like_a_comment()
    {
        $user = $this->sanctumSignIn();
        $comment = Comment::factory()->create();

        $response = $this->postJson($this->action('comments', $comment->id));

        $response->assertStatus(204);
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'likeable_id' => $comment->id,
            'likeable_type' => Comment::class,
        ]);
    }

    public function test_authenticated_user_can_unlike_a_comment()
    {
        $user = $this->sanctumSignIn();
        $comment = Comment::factory()->create();
        $comment->likes()->create(['user_id' => $user->id]);

        $response = $this->deleteJson($this->action('comments', $comment->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'likeable_id' => $comment->id,
            'likeable_type' => Comment::class,
        ]);
    }

    public function test_cannot_like_already_liked_item()
    {
        $user = $this->sanctumSignIn();
        $post = Post::factory()->create();
        $post->likes()->create(['user_id' => $user->id]);

        $this->postJson($this->action('posts', $post->id))->assertStatus(409);
        $this->assertDatabaseCount('likes', 1);
    }

    public function test_cannot_unlike_not_liked_item()
    {
        $this->sanctumSignIn();
        $post = Post::factory()->create();

        $this->deleteJson($this->action('posts', $post->id))->assertStatus(204);
    }

    public function test_cannot_like_non_existent_item()
    {
        $this->sanctumSignIn();

        $this->postJson($this->action('posts', 9999))->assertNotFound();
        $this->postJson($this->action('comments', 9999))->assertNotFound();
    }
}
