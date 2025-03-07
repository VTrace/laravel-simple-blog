<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_access_create_post_page()
    {
        $response = $this->get(route('posts.create'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_users_can_access_create_post_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('posts.create'));

        $response->assertStatus(200)
                ->assertSee('Create New Post');
    }

    /** @test */
    public function guest_users_cannot_submit_post_creation_form()
    {
        $response = $this->post(route('posts.store'), [
            'title' => 'Test Post',
            'body' => 'This is a test post.',
            'scheduled_at' => now()->addDay(),
            'is_draft' => false,
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('posts', 0);
    }

    /** @test */
    public function authenticated_users_can_create_a_new_post()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'New Post',
            'body' => 'This is a new post body.',
            'scheduled_at' => now()->addDay(),
            'is_draft' => false,
        ]);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseHas('posts', [
            'title' => 'New Post',
            'body' => 'This is a new post body.',
        ]);
    }
}
