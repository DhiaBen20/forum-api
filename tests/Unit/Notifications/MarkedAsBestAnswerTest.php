<?php

namespace Tests\Unit\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\MarkedAsBestAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkedAsBestAnswerTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_array(): void
    {
        $actor = User::factory()->create();
        $post = Post::factory()->for($actor)->create();
        $comment = Comment::factory()->for($post)->create();
        $reply = Comment::factory()->for($comment)->create();

        $notification1 = new MarkedAsBestAnswer($actor, $post, $comment);
        $this->assertEquals(
            [
                'actor' => $actor->only('id', 'name'),
                'post_id' => $post->id,
                'comment' => ['type' => 'comment_to_post', 'id' => $comment->id],
            ],
            $notification1->toArray($comment->user)
        );

        $notification2 = new MarkedAsBestAnswer($actor, $post, $reply);
        $this->assertEquals(
            [
                'actor' => $actor->only('id', 'name'),
                'post_id' => $post->id,
                'comment' => ['type' => 'reply_to_comment', 'id' => $reply->id],
            ],
            $notification2->toArray($comment->user)
        );
    }
}
