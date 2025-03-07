<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Enums\PostStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class PostSchedulingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scheduled_posts_are_hidden_from_listing_and_details()
    {
        // Create a published post
        $publishedPost = Post::factory()->create([
            'status' => PostStatus::Published,
            'published_at' => now(),
        ]);

        // Create a scheduled post
        $scheduledPost = Post::factory()->create([
            'status' => PostStatus::Scheduled,
            'scheduled_at' => now()->addDays(3),
            'published_at' => null,
        ]);

        // Ensure the published post is visible in the listing
        $this->get(route('posts.index'))
            ->assertSee($publishedPost->title)
            ->assertDontSee($scheduledPost->title);

        // Ensure the scheduled post is hidden from the detail page
        $this->get(route('posts.show', $scheduledPost))
            ->assertNotFound();
    }

    /** @test */
    public function draft_posts_are_hidden_from_listing_and_details()
    {
        // Create a draft post
        $draftPost = Post::factory()->create([
            'status' => PostStatus::Draft,
            'published_at' => null,
            'scheduled_at' => null,
        ]);

        // Ensure the draft post is hidden from the listing
        $this->get(route('posts.index'))
            ->assertDontSee($draftPost->title);

        // Ensure the draft post is hidden from the detail page
        $this->get(route('posts.show', $draftPost))
            ->assertNotFound();
    }

    /** @test */
    public function scheduled_posts_are_published_when_time_reaches()
    {
        // Create a scheduled post
        $scheduledPost = Post::factory()->create([
            'status' => PostStatus::Scheduled,
            'scheduled_at' => now()->subMinute(), // Make it past time
            'published_at' => null,
        ]);

        // Run the scheduled task manually
        $this->artisan('posts:publish-scheduled')
            ->expectsOutputToContain('Published: ' . $scheduledPost->title);

        // Refresh post model
        $scheduledPost->refresh();

        // Ensure the post is now published
        $this->assertEquals(PostStatus::Published, $scheduledPost->status);
        $this->assertNotNull($scheduledPost->published_at);
    }
}
