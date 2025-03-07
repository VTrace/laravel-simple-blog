<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        // Create posts for each user
        foreach ($users as $user) {
            Post::factory(5)->create([
                'author_id' => $user->id,
            ]);
        }
    }
}
