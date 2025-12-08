<?php

namespace Tests\Http\Comments;

use App\Http\Controllers\CommentController;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadCommentTest extends TestCase
{
    use RefreshDatabase;

    protected function action(array $params = []): string
    {
        return action([CommentController::class, 'index'], $params);
    }

    public function test_it_returns_bad_request_if_type_is_invalid(): void
    {
        $this->getJson($this->action(['type' => 'invalid', 'parent' => 1]))
            ->assertStatus(400);
    }

    public function test_it_returns_not_found_if_parent_does_not_exist(): void
    {
        $this->getJson($this->action(['type' => 'post', 'parent' => 999]))
            ->assertNotFound();
    }

    public function test_user_can_see_comments_for_a_post(): void
    {
        $post = Post::factory()->create();

        $comments = collect([5, 10, 15])->map(function ($minutes) use ($post) {
            $this->travel($minutes)->minutes();

            return Comment::factory()->create(['post_id' => $post->id]);
        });

        Comment::factory()->create();

        $response = $this->getJson($this->action(['type' => 'post', 'parent' => $post->id]));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.id', $comments[0]->id)
            ->assertJsonPath('data.1.id', $comments[1]->id)
            ->assertJsonPath('data.2.id', $comments[2]->id)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'body',
                        'user' => ['id', 'name', 'email'],
                        'likesCount',
                        'repliesCount',
                        'isLiked',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_user_can_see_replies_for_a_comment(): void
    {
        $comment = Comment::factory()->create();
        $replies = Comment::factory(2)->create([
            'comment_id' => $comment->id,
        ]);

        $response = $this->getJson($this->action(['type' => 'comment', 'parent' => $comment->id]));

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $replies[0]->id)
            ->assertJsonPath('data.1.id', $replies[1]->id);
    }
}
