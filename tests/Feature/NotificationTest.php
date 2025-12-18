<?php

namespace Tests\Feature;

use App\CommentType;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_cannot_access_notifications(): void
    {
        $this->getJson('/api/notifications/unread')
            ->assertUnauthorized();

        $this->patchJson('/api/notifications/mark-all-read')
            ->assertUnauthorized();

        $this->patchJson('/api/notifications/INVALID_ID/mark-read')
            ->assertUnauthorized();
    }

    public function test_user_can_get_unread_notifications(): void
    {
        $user = $this->sanctumSignIn();

        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        $user->notify(new CommentReceived($comment->user, CommentType::CommentToPost, $comment));

        $response = $this->getJson('/api/notifications/unread');

        $response->assertOk()
            ->assertJsonCount(1);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = $this->sanctumSignIn();

        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        $user->notify(new CommentReceived($comment->user, CommentType::CommentToPost, $comment));

        $this->assertEquals(1, $user->unreadNotifications()->count());

        $response = $this->patchJson('/api/notifications/mark-all-read');

        $response->assertNoContent();
        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_user_can_mark_single_notification_as_read(): void
    {
        $user = $this->sanctumSignIn();

        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        $user->notify(new CommentReceived($comment->user, CommentType::CommentToPost, $comment));

        $notification = $user->notifications()->first();

        $response = $this->patchJson("/api/notifications/{$notification->id}/mark-read");

        $response->assertNoContent();
        $this->assertNotNull($notification->fresh()->read_at);
        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_user_cannot_mark_others_notification_as_read(): void
    {
        $otherUser = User::factory()->create();

        // Notification for other user
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        $otherUser->notify(new CommentReceived($comment->user, CommentType::CommentToPost, $comment));
        $notification = $otherUser->notifications()->first();

        $user = User::factory()->create();
        $this->sanctumSignIn($user);

        $response = $this->patchJson("/api/notifications/{$notification->id}/mark-read");

        $response->assertNotFound();
    }
}
