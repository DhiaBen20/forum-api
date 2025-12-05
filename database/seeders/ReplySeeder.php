<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::factory()->create(['name' => 'Post Author']);
        $userA = User::factory()->create(['name' => 'Alice (Commenter)']);
        $userB = User::factory()->create(['name' => 'Bob (Replier)']);
        $userC = User::factory()->create(['name' => 'Charlie (Deep Replier)']);

        $channel = Channel::create([
            'name' => 'Laravel Architecture',
            'slug' => 'laravel-architecture',
        ]);

        $post = Post::create([
            'user_id' => $author->id,
            'channel_id' => $channel->id,
            'title' => 'Should I use Repositories or just Eloquent?',
            'slug' => 'repositories-vs-eloquent',
            'body' => "## The Eternal Debate\n\nI am building a medium-sized Laravel app. Everyone says 'Use Repositories' to decouple from the DB, but Eloquent is so nice.\n\nIs it really worth the extra boilerplate to wrap Eloquent in a Repository pattern?",
        ]);

        // comments to post
        Comment::create([
            'user_id' => $userC->id,
            'post_id' => $post->id,
            'body' => 'Just use Eloquent directly. YAGNI (You Aint Gonna Need It).',
        ]);

        $rootComment = Comment::create([
            'user_id' => $userA->id,
            'post_id' => $post->id,
            'body' => 'I prefer Repositories for testing purposes. It makes mocking data much easier when you inject an interface rather than using static Model calls.',
        ]);

        // Reply to comment
        $firstReply = Comment::create([
            'user_id' => $userB->id,
            'comment_id' => $rootComment->id,
            'reply_to_id' => null,
            'body' => "That is a valid point, Alice. But haven't you found that mocking Eloquent models has gotten easier in newer Laravel versions?",
        ]);

        // Reply to reply
        $nestedReply = Comment::create([
            'user_id' => $userA->id,
            'comment_id' => $rootComment->id,
            'reply_to_id' => $firstReply->id,
            'body' => 'It has gotten easier, yes. But I still like the separation of concerns. It keeps my controllers strictly responsible for HTTP logic, not query logic.',
        ]);

        Comment::create([
            'user_id' => $userC->id,
            'comment_id' => $rootComment->id,
            'reply_to_id' => $nestedReply->id,
            'body' => 'I think you are both over-engineering it! 😅',
        ]);
    }
}
