<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostValidationTest extends TestCase
{
    use RefreshDatabase; // Reset database setiap test dijalankan

    /** @test */
    public function post_title_must_be_60_characters_or_less()
    {
        // Create an authenticated user
        $user = User::factory()->create();

        // Attempt to create a post with a title longer than 60 characters
        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => str_repeat('A', 61), // 61 characters
            'body' => 'This is a valid post body.',
            'status' => 'draft',
        ]);

        // Assert validation error on the title field
        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function post_title_accepts_up_to_60_characters()
    {
        // Create an authenticated user
        $user = User::factory()->create();

        // Create a valid post with exactly 60 characters
        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => str_repeat('A', 60), // 60 characters
            'body' => 'This is a valid post body.',
            'status' => 'draft',
        ]);

        // Expect a successful redirect (assuming post creation redirects)
        $response->assertRedirect(route('home'));

        // Ensure the post is in the database
        $this->assertDatabaseHas('posts', [
            'title' => str_repeat('A', 60),
        ]);
    }
}
