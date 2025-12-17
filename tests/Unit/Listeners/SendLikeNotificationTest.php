<?php

namespace Tests\Unit\Listeners;

use App\Events\LikeStored;
use App\Listeners\SendLikeNotification;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\LikeReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendLikeNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_a_notification_when_a_user_likes_someone_elses_content(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create();

        $postEvent = new LikeStored($user, $post);
        $commentEvent = new LikeStored($user, $comment);
        $lisenter = new SendLikeNotification;

        $lisenter->handle($postEvent);
        Notification::assertSentTo($post->user, function (LikeReceived $notification) use ($post, $user) {
            return $notification->likeable->is($post) && $notification->actor->is($user);
        });

        $lisenter->handle($commentEvent);
        Notification::assertSentTo($comment->user, function (LikeReceived $notification) use ($comment, $user) {
            return $notification->likeable->is($comment) && $notification->actor->is($user);
        });
    }

    public function test_it_does_not_send_a_notification_when_a_user_likes_their_own_content(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $comment = Comment::factory()->for($user)->create();

        $postEvent = new LikeStored($user, $post);
        $commentEvent = new LikeStored($user, $comment);
        $lisenter = new SendLikeNotification;

        $lisenter->handle($postEvent);
        $lisenter->handle($commentEvent);

        Notification::assertNothingSent();
    }
}
