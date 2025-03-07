<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_users_see_login_and_register_links()
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Please');
        $response->assertSee('login');
        $response->assertSee('register');
    }

    public function test_authenticated_users_see_their_own_posts_including_drafts_and_scheduled()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // Membuat post milik user yang login
        $ownPublishedPost = Post::factory()->for($user, 'author')->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $ownDraftPost = Post::factory()->for($user, 'author')->create([
            'status' => 'draft',
            'published_at' => null,
        ]);

        $ownScheduledPost = Post::factory()->for($user, 'author')->create([
            'status' => 'scheduled',
            'published_at' => now()->addDays(3),
        ]);

        // Membuat post milik user lain
        $otherUserPost = Post::factory()->for($otherUser, 'author')->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        // Pastikan post milik user yang login muncul
        $response->assertSee($ownPublishedPost->title);
        $response->assertSee($ownDraftPost->title);
        $response->assertSee($ownScheduledPost->title);
        // Pastikan post milik user lain tidak muncul
        $response->assertDontSee($otherUserPost->title);
    }

    public function test_each_post_shows_status_label()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'author')->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertSee(ucfirst($post->status->value));
    }
}