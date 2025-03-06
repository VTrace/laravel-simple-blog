<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class PostPageVisibilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all users, including guests, can see only published posts in the listing.
     */
    public function test_all_users_can_see_only_published_posts_in_listing()
    {
        // Create a published post (status = 'published' and published_at in the past)
        $publishedPost = Post::factory()->create([
            'status' => 'published',
            'published_at' => Carbon::now()->subDay(),
        ]);

        // Create a scheduled post (status = 'published' but published_at in the future)
        $scheduledPost = Post::factory()->create([
            'status' => 'scheduled',
            'published_at' => Carbon::now()->addDay(),
        ]);

        // Create a draft post (status = 'draft' and published_at in the past)
        $draftPost = Post::factory()->create([
            'status' => 'draft',
            'published_at' => Carbon::now()->subDay(),
        ]);

        // Guest user should see only the published post
        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertSeeText($publishedPost->title);
        $response->assertDontSeeText($scheduledPost->title); // Should not be visible
        $response->assertDontSeeText($draftPost->title); // Should not be visible
    }

    /**
     * Test that all users, including guests, can see the details of a published post.
     */
    public function test_all_users_can_see_published_post_details()
    {
        // Create a published post
        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => Carbon::now()->subDay(),
        ]);

        // Guest user should be able to view the post details
        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertSeeText($post->title);
        $response->assertSeeText($post->body);
    }

    // /**
    //  * Test that unpublished (draft or scheduled) posts are NOT accessible.
    //  */
    public function test_unpublished_posts_are_not_accessible()
    {
        // Create a scheduled post (status = 'published', but published_at in the future)
        $scheduledPost = Post::factory()->create([
            'status' => 'scheduled',
            'scheduled_at' => Carbon::now()->addDay(),
        ]);

        // Create a draft post (status = 'draft', even if published_at is in the past)
        $draftPost = Post::factory()->create([
            'status' => 'draft',
            'scheduled_at' => Carbon::now()->subDay(),
        ]);

        // Both scheduled and draft posts should return 404
        $this->get(route('posts.show', $scheduledPost))->assertStatus(404);
        $this->get(route('posts.show', $draftPost))->assertStatus(404);
    }
}
