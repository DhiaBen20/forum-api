<?php

namespace Tests\Feature\Comments;

use App\Http\Controllers\CommentController;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateCommentTest extends TestCase
{
    use RefreshDatabase;

    protected function action(int $comment): string
    {
        return action([CommentController::class, 'update'], $comment);
    }

    public function test_guest_cannot_update_comment(): void
    {
        $this->patchJson($this->action(1), [])
            ->assertUnauthorized();
    }

    public function test_user_can_update_own_comment(): void
    {
        $comment = Comment::factory()->create();
        Sanctum::actingAs($comment->user);

        $response = $this->patchJson($this->action($comment->id), ['body' => 'Updated comment body']);

        $response->assertOk()
            ->assertJsonPath('body', 'Updated comment body')
            ->assertJsonPath('id', $comment->id);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Updated comment body',
        ]);
    }

    public function test_user_cannot_update_other_users_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();
        Sanctum::actingAs($user);

        $this->patchJson($this->action($comment->id), [])
            ->assertForbidden();
    }

    public function test_validation_errors(): void
    {
        $comment = Comment::factory()->create();
        Sanctum::actingAs($comment->user);

        $this->patchJson($this->action($comment->id), ['body' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['body']);

        $this->patchJson($this->action($comment->id), ['body' => str_repeat('a', 5001)])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['body']);
    }
}
