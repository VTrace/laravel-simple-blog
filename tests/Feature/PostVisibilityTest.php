<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostVisibilityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_can_view_the_post_listing_page()
    {
        // Arrange: Create multiple published posts
        Post::factory()->count(3)->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Act: Guest visits the post listing page
        $response = $this->get(route('posts.index'));

        // Assert: The response is successful and posts are visible
        $response->assertStatus(200);
        $response->assertSee(Post::first()->title);
    }

    /** @test */
    public function guest_users_can_view_a_post_detail_page()
    {
        // Arrange: Create a single published post
        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Act: Guest visits the post detail page
        $response = $this->get(route('posts.show', $post->slug));

        // Assert: The response is successful and contains the post content
        $response->assertStatus(200);
        $response->assertSee($post->title);
        $response->assertSee($post->content);
    }

    /** @test */
    public function draft_posts_are_not_visible_to_guest_users()
    {
        // Arrange: Create a draft post
        $post = Post::factory()->create([
            'status' => 'draft',
            'published_at' => null,
        ]);

        // Act: Guest tries to access the draft post
        $response = $this->get(route('posts.show', $post->slug));

        // Assert: The guest should receive a 404 Not Found error
        $response->assertNotFound();
    }
}
