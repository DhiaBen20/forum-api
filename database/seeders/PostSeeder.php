<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupedPostes = [
            [
                [
                    'slug' => 'welcome-to-the-forum',
                    'title' => 'Welcome to the Forum!',
                    'body' => "# Welcome to Our Community!\n\nHello everyone! Welcome to our **community forum**. This is a place where we can:\n\n- Share ideas\n- Ask questions\n- Help each other grow\n\nFeel free to introduce yourself and let's build a great community together! 🎉",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'So happy to be here! Looking forward to learning from everyone.',
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'Great initiative. Hello from London! 👋',
                        ],
                    ],
                ],
                [
                    'slug' => 'best-practices-for-online-communities',
                    'title' => 'Best Practices for Online Communities',
                    'body' => "## What Makes a Great Online Community?\n\nI wanted to share some thoughts on what makes a great online community:\n\n### Key Principles\n\n1. **Respectful communication** - Always be kind and considerate\n2. **Helpful responses** - Share knowledge and support others\n3. **Welcoming atmosphere** - Make everyone feel included\n\nWhat are your thoughts on building a positive community culture?",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "I think 'assuming good intent' is another crucial principle. Text can be easily misinterpreted.",
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "Agreed 100%. A welcoming atmosphere makes newbies feel safe to ask 'stupid' questions.",
                        ],
                    ],
                ],
                [
                    'slug' => 'how-to-get-started-here',
                    'title' => 'How to Get Started Here',
                    'body' => "## Quick Start Guide\n\nNew to the forum? Here's a quick guide to get you started:\n\n### Getting Started\n\n- **Browse channels** - Find topics that interest you\n- **Ask questions** - Don't hesitate to seek help\n- **Search first** - Check existing posts before creating new ones\n- **Be active** - Engage with the community\n\nHappy posting! 🚀",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "The 'Search First' rule is so important to keep the forum clean!",
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'Thanks for the guide. Is there a specific place to report bugs with the forum software?',
                        ],
                    ],
                ],
            ],
            [
                [
                    'slug' => 'understanding-arrow-functions-in-javascript',
                    'title' => 'Understanding Arrow Functions in JavaScript',
                    'body' => "## Arrow Functions in JavaScript\n\nArrow functions are one of the most popular features introduced in **ES6**. They provide a more concise syntax and lexically bind the `this` value.\n\n### Example\n\n```javascript\n// Regular function\nconst regular = function(x) {\n  return x * 2;\n};\n\n// Arrow function\nconst arrow = (x) => x * 2;\n```\n\n### When to Use Each?\n\nHowever, they're not always a drop-in replacement for regular functions. When would you use arrow functions vs regular functions?",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'I almost exclusively use arrow functions now, except when I need a dynamic `this` context in an object method.',
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "Don't forget that arrow functions don't have their own `arguments` object! That caught me off guard once.",
                        ],
                    ],
                ],
                [
                    'slug' => 'async-await-vs-promises-which-to-use',
                    'title' => 'Async/Await vs Promises: Which to Use?',
                    'body' => "## Async/Await vs Promises\n\nBoth `async/await` and promises are powerful tools for handling asynchronous operations in JavaScript.\n\n### Async/Await\n\n- ✅ Cleaner, more readable code\n- ✅ Easier error handling with try/catch\n- ✅ More intuitive for sequential operations\n\n### Promises\n\n- ✅ More control over the flow\n- ✅ Better for parallel operations\n- ✅ More explicit chaining\n\n### Example\n\n```javascript\n// Using Promises\nfetch('/api/data')\n  .then(response => response.json())\n  .then(data => console.log(data));\n\n// Using async/await\nconst response = await fetch('/api/data');\nconst data = await response.json();\nconsole.log(data);\n```\n\nWhat are your experiences with each approach? When do you prefer one over the other?",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'Async/await makes the code look synchronous, which is so much easier to reason about.',
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'I still use Promises when I need to fire off multiple requests at once with `Promise.all()`, but await for everything else.',
                        ],
                    ],
                ],
                [
                    'slug' => 'modern-javascript-features-you-should-know',
                    'title' => 'Modern JavaScript Features You Should Know',
                    'body' => "## Modern JavaScript Features\n\nJavaScript has evolved significantly over the years. Here are some game-changing features:\n\n### Optional Chaining (`?.`)\n\n```javascript\nconst user = { profile: { name: 'John' } };\nconst name = user?.profile?.name; // Safe access\n```\n\n### Nullish Coalescing (`??`)\n\n```javascript\nconst value = user.name ?? 'Anonymous'; // Only null/undefined\n```\n\n### Template Literals\n\n```javascript\nconst greeting = `Hello, \${name}!`; // Clean string interpolation\n```\n\nThese features have made our code more **elegant** and **safer**. What are your favorite modern JavaScript features, and how have they improved your development workflow?",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "Optional chaining `?.` basically eliminated 90% of my 'cannot read property of undefined' errors.",
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "Destructuring assignment is another huge one for me. Can't live without it!",
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "I love Nullish Coalescing. It's way safer than `||` because it respects empty strings and zero as valid values.",
                        ],
                    ],
                ],
            ],

            [
                [
                    'slug' => 'laravel-eloquent-best-practices',
                    'title' => 'Laravel Eloquent Best Practices',
                    'body' => "## Laravel Eloquent Best Practices\n\nEloquent is Laravel's powerful **ORM** that makes database interactions a breeze.\n\n### Key Best Practices\n\n1. **Eager Loading** - Prevent N+1 queries\n   ```php\n   \$posts = Post::with('user', 'comments')->get();\n   ```\n\n2. **Leverage Relationships** - Use relationships effectively\n   ```php\n   \$user->posts()->where('published', true)->get();\n   ```\n\n3. **Use Scopes** - Reusable query logic\n   ```php\n   Post::published()->recent()->get();\n   ```\n\nWhat Eloquent tips have you found most valuable?",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'The N+1 problem is the silent killer of app performance. The Laravel Debugbar package is essential for spotting these.',
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'Local Scopes are amazing for keeping your controllers clean and readable.',
                        ],
                    ],
                ],
                [
                    'slug' => 'understanding-laravel-service-containers',
                    'title' => 'Understanding Laravel Service Containers',
                    'body' => "## Understanding Laravel Service Container\n\nThe service container is one of Laravel's most powerful features, providing:\n\n- **Dependency Injection** - Automatic class resolution\n- **Binding** - Register services and their implementations\n- **Resolution** - Automatic dependency resolution\n\n### Example\n\n```php\n// Binding in a service provider\n\$this->app->bind(RepositoryInterface::class, UserRepository::class);\n\n// Automatic resolution\nclass UserController\n{\n    public function __construct(RepositoryInterface \$repo) {}\n}\n```\n\nIt helps manage class dependencies and makes your code more **testable** and **maintainable**. How do you leverage the service container in your Laravel applications?",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'It took me a while to wrap my head around the Service Container, but once I did, it changed how I write PHP.',
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'This is crucial for unit testing. Mocking dependencies becomes so much easier when you inject interfaces.',
                        ],
                    ],
                ],
                [
                    'slug' => 'laravel-routing-and-middleware-tips',
                    'title' => 'Laravel Routing and Middleware Tips',
                    'body' => "## Laravel Routing and Middleware Tips\n\nLaravel's routing system is **flexible** and **powerful**. Here are some key features:\n\n### Route Model Binding\n\n```php\nRoute::get('/posts/{post}', function (Post \$post) {\n    return \$post;\n});\n```\n\n### Middleware Groups\n\n```php\nRoute::middleware(['auth', 'verified'])->group(function () {\n    Route::get('/dashboard', [DashboardController::class, 'index']);\n});\n```\n\n### Route Caching\n\n- Use `php artisan route:cache` in production\n- Improves performance significantly\n\nShare your favorite routing patterns and middleware strategies that have helped streamline your Laravel projects!",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "Route model binding with custom keys (e.g., slugs instead of IDs) is super handy: `Route::get('/posts/{post:slug}'...)`",
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "Just a reminder: never run route caching in development or you won't see your changes!",
                        ],
                    ],
                ],
            ],
            [
                [
                    'slug' => 'database-indexing-strategies-for-performance',
                    'title' => 'Database Indexing Strategies for Performance',
                    'body' => "## Database Indexing Strategies\n\nProper indexing can **dramatically** improve query performance, but over-indexing can slow down writes.\n\n### When to Index\n\n- ✅ Frequently queried columns\n- ✅ Foreign keys\n- ✅ Columns used in WHERE clauses\n- ✅ Columns used in JOIN operations\n\n### When NOT to Index\n\n- ❌ Rarely queried columns\n- ❌ Frequently updated columns\n- ❌ Small tables\n\n### Tools for Analysis\n\n- Query execution plans\n- Database profiling tools\n- Slow query logs\n\nHow do you approach indexing in your databases? What tools or techniques do you use to identify missing indexes?",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'Learning to read an `EXPLAIN` plan was the best investment I made for database optimization.',
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'People often forget that composite indexes (multi-column) depend heavily on the order of columns.',
                        ],
                    ],
                ],
                [
                    'slug' => 'normalization-vs-denormalization-when-to-use',
                    'title' => 'Normalization vs Denormalization: When to Use',
                    'body' => "## Normalization vs Denormalization\n\n### Normalization\n\n**Pros:**\n- Reduces redundancy\n- Ensures data integrity\n- Easier to maintain\n\n**Cons:**\n- More complex queries\n- Potential performance overhead\n\n### Denormalization\n\n**Pros:**\n- Improved read performance\n- Simpler queries\n- Faster data retrieval\n\n**Cons:**\n- Data redundancy\n- Update complexity\n- Potential inconsistency\n\n### When to Use Each?\n\nThe choice depends on your use case:\n\n- **Normalize** for transactional systems\n- **Denormalize** for read-heavy applications\n\nWhen have you chosen to denormalize, and what were the trade-offs? Share your experiences with both approaches.",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "I usually stick to 3NF (Normalized) until I hit a specific performance bottleneck that caching can't solve.",
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "Denormalization is great for reporting dashboards where you don't want to join 15 tables on every page load.",
                        ],
                    ],
                ],
                [
                    'slug' => 'transaction-management-and-data-consistency',
                    'title' => 'Transaction Management and Data Consistency',
                    'body' => "## Transaction Management and Data Consistency\n\nTransactions ensure data consistency by grouping operations that must **succeed or fail together**.\n\n### Key Concepts\n\n1. **ACID Properties**\n   - Atomicity\n   - Consistency\n   - Isolation\n   - Durability\n\n2. **Isolation Levels**\n   - Read Uncommitted\n   - Read Committed\n   - Repeatable Read\n   - Serializable\n\n3. **Deadlock Prevention**\n   - Consistent lock ordering\n   - Timeout strategies\n   - Retry mechanisms\n\n### Example (Laravel)\n\n```php\nDB::transaction(function () {\n    \$user = User::create([...]);\n    \$profile = Profile::create([...]);\n});\n```\n\nWhat are your best practices for managing transactions in your applications?",
                    'user_id' => User::factory()->create()->id,
                    'comments' => [
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'Transactions are non-negotiable when dealing with anything financial.',
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => 'The closure syntax in Laravel makes this so easy. I remember having to manually `begin`, `commit`, and `rollback` in older legacy PHP.',
                        ],
                        [
                            'user_id' => User::factory()->create()->id,
                            'body' => "Be careful with external API calls inside a database transaction! That's a recipe for locking issues.",
                        ],
                    ],
                ],
            ],
        ];

        foreach ($groupedPostes as $idx => $group) {
            $channel = Channel::offset($idx)->first();

            assert($channel instanceof Channel);

            foreach ($group as $postData) {
                $post = $channel->posts()->create(Arr::except($postData, 'comments'));

                $post->comments()->createMany($postData['comments']);
            }

            $idx++;
        }
    }
}
