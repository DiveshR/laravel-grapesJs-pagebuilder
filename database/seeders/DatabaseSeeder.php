<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Blog;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 10 blog posts, associating them with the created Test User or other users
        Blog::factory(5)->for($user)->create(); // Create 5 blogs for the Test User
        Blog::factory(5)->create(); // Create 5 more blogs, associated with newly created random users
    }
}
