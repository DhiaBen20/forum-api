<?php

namespace Tests\Http\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReadPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_posts(): void
    {
        $posts = [];
        for ($i = 0; $i < 3; $i++) {
            $this->travel(1)->minutes();
            $posts[] = Post::factory()->create();
        }

        $response = $this->getJson(route('posts.index'));

        $response->assertOk();

        foreach ($response->json('data') as $index => $post) {
            $expectedPost = $posts[2 - $index];
            $author = $expectedPost->user;
            assert($author !== null);

            $excerpt = Str::of($expectedPost->body)->markdown()->stripTags()->replace("\n", '')->excerpt();
            $this->assertEquals($post['id'], $expectedPost->id);
            $this->assertEquals($post['title'], $expectedPost->title);
            $this->assertEquals($post['slug'], $expectedPost->slug);
            $this->assertEquals($post['excerpt'], $excerpt);
            $this->assertEquals($post['user']['id'], $author->id);
            $this->assertEquals($post['user']['name'], $author->name);
            $this->assertEquals($post['user']['email'], $author->email);
            $this->assertEquals($post['createdAt'], $expectedPost->created_at->toISOString());
            $this->assertEquals($post['updatedAt'], $expectedPost->updated_at->toISOString());
        }
    }

    public function test_can_show_single_post(): void
    {
        $post = Post::factory()->create();
        assert($post->user !== null);

        $response = $this->getJson(route('posts.show', $post->slug));

        $response->assertOk()
            ->assertJson([
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'body' => $post->body,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'email' => $post->user->email,
                ],
            ]);
    }

    public function test_show_returns_404_if_post_not_found(): void
    {
        $response = $this->getJson(route('posts.show', 'non-existent-slug'));

        $response->assertNotFound();
    }
}
