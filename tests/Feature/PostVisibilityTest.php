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
    public function guest_users_see_login_and_registration_links()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee('Register');
    }

    /** @test */
    public function authenticated_users_see_their_own_posts_including_drafts_and_scheduled()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $publishedPost = Post::factory()->create([
            'user_id' => $user->id,
            'status' => 'published'
        ]);

        $draftPost = Post::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft'
        ]);

        $scheduledPost = Post::factory()->create([
            'user_id' => $user->id,
            'status' => 'scheduled'
        ]);

        $otherUserPost = Post::factory()->create([
            'user_id' => $otherUser->id,
            'status' => 'published'
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($publishedPost->title);
        $response->assertSee($draftPost->title);
        $response->assertSee($scheduledPost->title);
        $response->assertDontSee($otherUserPost->title);
    }

    /** @test */
    public function each_post_displays_a_status_label()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft'
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Draft'); // Pastikan ada label status di setiap post
    }
}