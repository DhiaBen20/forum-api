<?php

namespace Tests\Unit\Listeners;

use App\Events\PostAnswered;
use App\Listeners\SendMarkedAsBestAnswerNotification;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\MarkedAsBestAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendMarkedAsBestAnswerNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_notifies_a_user_when_his_comment_is_marked(): void
    {
        Notification::fake(MarkedAsBestAnswer::class);

        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        $post->update(['best_answer_id' => $comment->id]);

        $event = new PostAnswered($comment->user, $post, $comment);
        $listener = new SendMarkedAsBestAnswerNotification;

        $listener->handle($event);

        Notification::assertSentTo($comment->user, MarkedAsBestAnswer::class);
    }

    public function test_it_doesnot_notify_a_user_when_his_comment_is_unmarked(): void
    {
        Notification::fake(MarkedAsBestAnswer::class);

        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();

        $event = new PostAnswered($comment->user, $post, $comment);
        $listener = new SendMarkedAsBestAnswerNotification;

        $listener->handle($event);

        Notification::assertNothingSentTo($comment->user);
    }

    public function test_it_doesnot_notify_the_post_owner_when_he_marks_his_comment(): void
    {
        Notification::fake(MarkedAsBestAnswer::class);

        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $comment = Comment::factory()->for($post)->for($user)->create();
        $post->update(['best_answer_id' => $comment->id]);

        $event = new PostAnswered($comment->user, $post, $comment);
        $listener = new SendMarkedAsBestAnswerNotification;

        $listener->handle($event);

        Notification::assertNothingSentTo($comment->user);
    }
}
