<?php

namespace Tests\Feature\Posts;

use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    protected function action(Post $post): string
    {
        return action([PostController::class, 'destroy'], ['post' => $post]);
    }

    public function test_user_can_delete_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $this->deleteJson($this->action($post))->assertNoContent();

        $this->assertDatabaseMissing('posts', $post->only('id'));
    }

    public function test_user_cannot_delete_other_users_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $this->deleteJson($this->action($post))->assertForbidden();

        $this->assertDatabaseHas('posts', $post->only('id'));
    }

    public function test_guest_cannot_delete_post(): void
    {
        $post = Post::factory()->create();

        $this->deleteJson($this->action($post))->assertUnauthorized();

        $this->assertDatabaseHas('posts', $post->only('id'));
    }
}
