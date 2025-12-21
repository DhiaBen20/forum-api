<?php

namespace Tests\Feature;

use App\Events\PostAnswered;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MarkBestAnswerTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_owner_can_mark_comment_as_best_answer()
    {
        Event::fake([PostAnswered::class]);
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $this->sanctumSignIn($user);
        $response = $this->patchJson("/api/comments/{$comment->id}/best-answer");

        $response->assertStatus(200);
        $this->assertEquals($comment->id, $post->fresh()->best_answer_id);
        Event::assertDispatched(PostAnswered::class);
    }

    public function test_post_owner_can_unmark_best_answer()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $post->update(['best_answer_id' => $comment->id]);

        $this->sanctumSignIn($user);

        $response = $this->patchJson("/api/comments/{$comment->id}/best-answer");

        $response->assertStatus(200);
        $this->assertNull($post->fresh()->best_answer_id);
    }

    public function test_marking_new_answer_replaces_old()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment1 = Comment::factory()->create(['post_id' => $post->id]);
        $comment2 = Comment::factory()->create(['post_id' => $post->id]);

        $post->update(['best_answer_id' => $comment1->id]);

        $this->sanctumSignIn($user);

        $response = $this->patchJson("/api/comments/{$comment2->id}/best-answer");

        $response->assertStatus(200);
        $this->assertEquals($comment2->id, $post->fresh()->best_answer_id);
    }

    public function test_post_owner_can_mark_nested_comment_as_best_answer()
    {

        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $parentComment = Comment::factory()->for($post)->create();
        $nestedComment = Comment::factory()->for($parentComment)->create();

        $this->sanctumSignIn($user);

        $response = $this->patchJson("/api/comments/{$nestedComment->id}/best-answer");

        $response->assertStatus(200);
        $this->assertEquals($nestedComment->id, $post->fresh()->best_answer_id);
    }

    public function test_only_owner_can_mark_best_answer()
    {
        Event::fake([PostAnswered::class]);

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $owner->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $this->patchJson("/api/comments/{$comment->id}/best-answer")->assertUnauthorized();

        $this->sanctumSignIn($otherUser);

        $this->patchJson("/api/comments/{$comment->id}/best-answer")->assertStatus(403);
        $this->assertNull($post->fresh()->best_answer_id);
        Event::assertNotDispatched(PostAnswered::class);
    }
}
