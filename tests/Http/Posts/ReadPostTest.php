<?php

namespace Tests\Http\Posts;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadPostTest extends TestCase
{
    use RefreshDatabase;

    protected function postResponsekeys(?bool $excerpt = false): array
    {
        return [
            'id',
            'title',
            'slug',
            $excerpt ? 'excerpt' : 'body',
            'createdAt',
            'updatedAt',
            'isLiked',
            'likesCount',
            'commentsCount',
            'user' => ['id', 'name', 'email'],
        ];
    }

    protected function assertPostIsLiked(Post $post, bool $liked = false): void
    {
        $this->getJson(route('posts.index'))
            ->assertJsonPath('data.0.isLiked', $liked);

        $this->getJson(route('posts.show', $post->slug))
            ->assertJsonPath('isLiked', $liked);
    }

    protected function assertPostLikesCount(Post $post, int $count)
    {
        $this->getJson(route('posts.index'))
            ->assertJsonPath('data.0.likesCount', $count);

        $this->getJson(route('posts.show', $post->slug))
            ->assertJsonPath('likesCount', $count);
    }

    protected function assertCommentCount(Post $post, int $count)
    {
        $this->getJson(route('posts.index'))
            ->assertJsonPath('data.0.commentsCount', $count);

        $this->getJson(route('posts.show', $post->slug))
            ->assertJsonPath('commentsCount', $count);
    }

    public function test_can_list_posts(): void
    {
        $posts = [];
        for ($i = 0; $i < 3; $i++) {
            $this->travel(1)->minutes();
            $posts[] = Post::factory()->create();
        }

        $response = $this->getJson(route('posts.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['*' => $this->postResponsekeys(true)],
            ]);

        $response->assertJsonPath('data.0.id', $posts[2]->id);
        $response->assertJsonPath('data.1.id', $posts[1]->id);
        $response->assertJsonPath('data.2.id', $posts[0]->id);
    }

    public function test_can_show_single_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->getJson(route('posts.show', $post->slug));

        $response->assertOk()
            ->assertJsonStructure($this->postResponsekeys())
            ->assertJson(['id' => $post->id]);
    }

    public function test_user_can_see_if_he_liked_a_post(): void
    {
        $post = Post::factory()->create();

        $this->assertPostIsLiked($post, false);

        $user = $this->sanctumSignIn();

        $this->assertPostIsLiked($post, false);

        $post->likes()->create(['user_id' => $user->id]);
        $this->assertPostIsLiked($post, true);
    }

    public function test_user_can_see_the_number_of_likes(): void
    {
        $post = Post::factory()->create();

        $this->assertPostLikesCount($post, 0);

        Like::factory()->count(3)->for($post, 'likeable')->create();

        $this->assertPostLikesCount($post, 3);
    }

    public function test_user_can_see_the_number_of_comment(): void
    {
        $post = Post::factory()->create();

        $this->assertCommentCount($post, 0);

        Comment::factory()->count(3)->for($post)->create();

        $this->assertCommentCount($post, 3);
    }

    public function test_show_returns_404_if_post_not_found(): void
    {
        $response = $this->getJson(route('posts.show', 'non-existent-slug'));

        $response->assertNotFound();
    }
}
