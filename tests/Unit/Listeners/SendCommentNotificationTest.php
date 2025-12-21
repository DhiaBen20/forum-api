<?php

namespace Tests\Unit\Listeners;

use App\Events\CommentStored;
use App\Listeners\SendCommentNotification;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendCommentNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_a_notification_when_a_user_comments_on_someone_elses_content(): void
    {
        Notification::fake();

        $notifiable1 = User::factory()->create();
        $notifiable2 = User::factory()->create();
        $notifiable3 = User::factory()->create();

        $targetPost = Post::factory()->for($notifiable1)->create();
        $targetComment = Comment::factory()->for($notifiable2)->create();
        $targetReply = Comment::factory()->reply()->for($notifiable3)->create();

        $comment1 = Comment::factory()->for($targetPost)->create();
        $comment2 = Comment::factory()->for($targetComment, 'comment')->create();
        $comment3 = Comment::factory()->state(['comment_id' => $targetReply, 'reply_to_id' => $targetReply])->create();

        $event1 = new CommentStored($comment1->user, $comment1);
        $event2 = new CommentStored($comment2->user, $comment2);
        $event3 = new CommentStored($comment3->user, $comment3);

        $listener = new SendCommentNotification;
        $listener->handle($event1);
        $listener->handle($event2);
        $listener->handle($event3);

        Notification::assertSentTo($notifiable1, CommentReceived::class);
        Notification::assertSentTo($notifiable2, CommentReceived::class);
        Notification::assertSentTo($notifiable3, CommentReceived::class);
    }

    public function test_it_does_not_send_a_notification_when_a_user_comments_on_their_own_content(): void
    {
        Notification::fake([CommentReceived::class]);

        $user = User::factory()->create();

        Post::factory()->for($user)->create();

        $comment = Comment::factory()->for($user)->create();

        $event = new CommentStored($comment->user, $comment);

        $listener = new SendCommentNotification;
        $listener->handle($event);

        Notification::assertNothingSent();
    }
}
