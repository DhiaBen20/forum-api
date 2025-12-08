<?php

namespace Tests\Http\Posts;

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

    public function test_show_returns_404_if_post_not_found(): void
    {
        $response = $this->getJson(route('posts.show', 'non-existent-slug'));

        $response->assertNotFound();
    }
}
