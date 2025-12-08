<?php

namespace Tests\Http\Comments;

use App\Http\Controllers\CommentController;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteCommentTest extends TestCase
{
    use RefreshDatabase;

    protected function action($id): string
    {
        return action([CommentController::class, 'destroy'], $id);
    }

    public function test_user_can_delete_own_comment(): void
    {
        $comment = Comment::factory()->create();
        Sanctum::actingAs($comment->user);

        $this->assertDatabaseCount('comments', 1);
        $this->deleteJson($this->action($comment->id))->assertNoContent();
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_user_cannot_delete_other_users_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();
        Sanctum::actingAs($user);

        $this->deleteJson($this->action($comment->id))->assertForbidden();
    }

    public function test_guest_cannot_delete_comment(): void
    {
        $this->deleteJson($this->action(1))->assertUnauthorized();
    }
}
