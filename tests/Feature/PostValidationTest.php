<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_title_cannot_exceed_60_characters(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'title' => str_repeat('A', 61), // 61 characters
            'body' => 'Valid content',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_post_title_within_limit_is_valid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posts.store'), [
            'title' => str_repeat('A', 60), // 60 characters
            'body' => 'Valid content',
            'status' => 'draft',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => str_repeat('A', 60)]);
    }
}
