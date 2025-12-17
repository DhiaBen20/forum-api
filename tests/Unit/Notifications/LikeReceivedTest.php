<?php

namespace Tests\Unit\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\LikeReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeReceivedTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_array(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->comment()->create();
        $reply = Comment::factory()->reply()->create();

        $PostLikeNotification = new LikeReceived($post, $user);

        $this->assertEquals(
            [
                'target' => ['type' => 'post', 'id' => $post->id],
                'actor' => $user->only('id', 'name'),
            ],
            $PostLikeNotification->toArray($post->user)
        );

        $commentLikeNotification = new LikeReceived($comment, $user);

        $this->assertEquals(
            [
                'target' => ['type' => 'comment', 'id' => $comment->id],
                'actor' => $user->only('id', 'name'),
            ],
            $commentLikeNotification->toArray($comment->user)
        );

        $replyLikeNotification = new LikeReceived($reply, $user);

        $this->assertEquals(
            [
                'target' => ['type' => 'reply', 'id' => $reply->id],
                'actor' => $user->only('id', 'name'),
            ],
            $replyLikeNotification->toArray($reply->user)
        );
    }
}
