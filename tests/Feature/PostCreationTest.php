<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that authenticated users can create posts.
     */
    public function test_authenticated_users_can_create_posts(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Simulate authentication
        $this->actingAs($user);

        // Define post data
        $postData = [
            'title' => 'My First Post',
            'body' => 'This is the content of my first post.',
            'status' => 'published',
            'published_at' => now(),
        ];

        // Send a POST request to create the post
        $response = $this->post(route('posts.store'), $postData);

        // Assert the response is a redirect (successful creation)
        $response->assertRedirect(route('posts.index'));

        // Verify the post exists in the database
        $this->assertDatabaseHas('posts', [
            'title' => 'My First Post',
            'body' => 'This is the content of my first post.',
            'status' => 'published',
        ]);
    }

    /**
     * Test that guest users cannot create posts.
     */
    public function test_guest_users_cannot_create_posts(): void
    {
        // Define post data
        $postData = [
            'title' => 'Unauthorized Post',
            'body' => 'This should not be created.',
            'status' => 'published',
            'published_at' => now(),
        ];

        // Send a POST request without authentication
        $response = $this->post(route('posts.store'), $postData);

        // Assert the request is redirected to login
        $response->assertRedirect(route('login'));

        // Ensure the post does not exist in the database
        $this->assertDatabaseMissing('posts', ['title' => 'Unauthorized Post']);
    }
}
