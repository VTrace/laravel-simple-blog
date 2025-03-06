<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostStatusLabelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that each post displays the correct status label.
     */
    public function test_each_post_displays_correct_status_label()
    {
        // Create a user
        $user = User::factory()->create();

        // Create posts with different statuses
        $publishedPost = Post::factory()->for($user, 'author')->create(['status' => 'published']);
        $draftPost = Post::factory()->for($user, 'author')->create(['status' => 'draft']);
        $scheduledPost = Post::factory()->for($user, 'author')->create(['status' => 'scheduled']);

        // Authenticate the user
        $this->actingAs($user);

        // Visit the post listing page
        $response = $this->get(route('home'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the correct status labels are visible for each post
        $response->assertSeeText($publishedPost->title);
        $response->assertSeeText('Published'); // Assuming status label is 'Published'
        
        $response->assertSeeText($draftPost->title);
        $response->assertSeeText('Draft'); // Assuming status label is 'Draft'
        
        $response->assertSeeText($scheduledPost->title);
        $response->assertSeeText('Scheduled'); // Assuming status label is 'Scheduled'
    }
}
