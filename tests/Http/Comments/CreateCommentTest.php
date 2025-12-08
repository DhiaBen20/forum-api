<?php

namespace Tests\Http\Comments;

use App\CommentType;
use App\Http\Controllers\CommentController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    protected function action(): string
    {
        return action([CommentController::class, 'store']);
    }

    public function test_guest_cannot_create_comment(): void
    {
        $this->postJson($this->action(), [])
            ->assertUnauthorized();
    }

    public function test_validation_errors(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Required fields
        $this->postJson($this->action(), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['body', 'type']);

        // Invalid type
        $this->postJson($this->action(), [
            'body' => 'Test Body',
            'type' => 'invalid_type',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);

        // Missing post_id for CommentToPost
        $this->postJson($this->action(), [
            'body' => 'Test Body',
            'type' => CommentType::CommentToPost->value,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['post']);

        // Missing comment_id for ReplyToComment
        $this->postJson($this->action(), [
            'body' => 'Test Body',
            'type' => CommentType::ReplyToComment->value,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['comment']);

        // Missing replyTo for ReplyToReply
        $this->postJson($this->action(), [
            'body' => 'Test Body',
            'type' => CommentType::ReplyToReply->value,
            'comment' => 1, // Providing comment but missing replyTo
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['replyTo']);
    }

    public function test_user_can_comment_on_post(): void
    {
        $user = $this->sanctumSignIn();
        $post = Post::factory()->create();

        $response = $this->postJson($this->action(), [
            'body' => 'My comment body',
            'type' => CommentType::CommentToPost->value,
            'post' => $post->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('body', 'My comment body');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => 'My comment body',
            'comment_id' => null,
            'reply_to_id' => null,
        ]);
    }

    public function test_user_can_reply_to_comment(): void
    {
        $user = $this->sanctumSignIn();
        $comment = Comment::factory()->create();

        $response = $this->postJson($this->action(), [
            'body' => 'My reply body',
            'type' => CommentType::ReplyToComment->value,
            'comment' => $comment->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('body', 'My reply body');

        $this->assertDatabaseHas('comments', [
            'comment_id' => $comment->id,
            'user_id' => $user->id,
            'body' => 'My reply body',
            'reply_to_id' => null,
            'post_id' => null,
        ]);
    }

    public function test_user_can_reply_to_reply(): void
    {
        $user = $this->sanctumSignIn();
        $parentComment = Comment::factory()->create();
        $targetReply = Comment::factory()->create([
            'comment_id' => $parentComment->id,
        ]);

        $response = $this->postJson($this->action(), [
            'body' => 'My nested reply',
            'type' => CommentType::ReplyToReply->value,
            'comment' => $parentComment->id,
            'replyTo' => $targetReply->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('body', 'My nested reply');

        $this->assertDatabaseHas('comments', [
            'comment_id' => $parentComment->id,
            'reply_to_id' => $targetReply->id,
            'user_id' => $user->id,
            'body' => 'My nested reply',
            'post_id' => null,
        ]);
    }
}
