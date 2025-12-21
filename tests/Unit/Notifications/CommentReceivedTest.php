<?php

namespace Tests\Unit\Notifications;

use App\CommentType;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentReceivedTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_array(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        $reply = Comment::factory()->for($comment, 'comment')->create();

        $comment1 = Comment::factory()->for($post)->create();
        $notification1 = new CommentReceived($comment1->user, $comment1);

        $this->assertEquals(
            [
                'actor' => $comment1->user->only('id', 'name'),
                'comment' => ['id' => $comment1->id, 'type' => CommentType::CommentToPost->value],
                'post_id' => $post->id,
            ],
            $notification1->toArray($post->user)
        );

        $comment2 = Comment::factory()->for($comment, 'comment')->create();
        $notification2 = new CommentReceived($comment2->user, $comment2);

        $this->assertEquals(
            [
                'actor' => $comment2->user->only('id', 'name'),
                'comment' => ['id' => $comment2->id, 'type' => CommentType::ReplyToComment->value],
                'post_id' => $post->id,
            ],
            $notification2->toArray($comment->user)
        );

        $comment3 = Comment::factory()->for($comment, 'comment')->create(['reply_to_id' => $reply->id]);
        $notification3 = new CommentReceived($comment3->user, $comment3);

        $this->assertEquals(
            [
                'actor' => $comment3->user->only('id', 'name'),
                'comment' => ['id' => $comment3->id, 'type' => CommentType::ReplyToReply->value],
                'post_id' => $post->id,
            ],
            $notification3->toArray($reply->user)
        );
    }
}
