<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'General Discussion', 'slug' => 'general-discussion'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'Database', 'slug' => 'database'],
        ];

        foreach ($data as $channel) {
            Channel::create($channel);
        }
    }
}
