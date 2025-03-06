<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class DraftSchedulingTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_posts_are_visible_to_all(): void
    {
        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('posts.index'));
        $response->assertSee($post->title);

        $response = $this->get(route('posts.show', $post));
        $response->assertSee($post->title);
    }

    public function test_drafts_are_hidden_from_listing_and_detail_pages(): void
    {
        $post = Post::factory()->create(['status' => 'draft']);

        $response = $this->get(route('posts.index'));
        $response->assertDontSee($post->title);

        $response = $this->get(route('posts.show', $post));
        $response->assertNotFound();
    }

    public function test_scheduled_posts_are_hidden_until_published(): void
    {
        $post = Post::factory()->create([
            'status' => 'scheduled',
            'published_at' => now()->addDay(),
        ]);

        $response = $this->get(route('posts.index'));
        $response->assertDontSee($post->title);

        $response = $this->get(route('posts.show', $post));
        $response->assertNotFound();
    }
}
