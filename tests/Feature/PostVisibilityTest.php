<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostVisibilityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_see_all_their_own_posts_including_drafts_and_scheduled()
    {
        // Create a user
        $user = User::factory()->create();

        // Create posts for this user (including drafts and scheduled posts)
        Post::factory()->for($user, 'author')->count(3)->create([
            'status' => 'published',
        ]);
        Post::factory()->for($user, 'author')->create([
            'status' => 'draft',
        ]);
        Post::factory()->for($user, 'author')->create([
            'status' => 'scheduled',
        ]);

        // Authenticate the user
        $this->actingAs($user);

        // Visit the post listing page
        $response = $this->get(route('home'));

        // Assert all posts (including drafts and scheduled) are visible
        $response->assertStatus(200);
        $response->assertSeeText(Post::where('author_id', $user->id)->pluck('title')->toArray());
    }

    /** @test */
    public function guest_user_sees_login_and_register_links_instead_of_posts()
    {
        // Create some published posts
        Post::factory()->count(3)->create([
            'status' => 'published',
        ]);

        // Visit the post listing page as a guest
        $response = $this->get(route('home'));

        // Assert guest sees login and register links
        $response->assertStatus(200);
        $response->assertSeeText('login');
        $response->assertSeeText('register');

        // Assert posts are NOT visible
        $response->assertDontSee(Post::pluck('title')->toArray());
    }
}
