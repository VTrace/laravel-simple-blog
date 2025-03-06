<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAuthorActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_can_update_post(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $author->id]);

        $this->actingAs($author);

        $response = $this->put(route('posts.update', $post), [
            'title' => 'Updated Title',
            'body' => 'Updated content',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    }

    public function test_non_author_cannot_update_post(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $author->id]);

        $this->actingAs($otherUser);

        $response = $this->put(route('posts.update', $post), ['title' => 'Invalid Update']);
        $response->assertStatus(302);
    }

    public function test_author_can_delete_post(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $author->id]);

        $this->actingAs($author);

        $response = $this->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_non_author_cannot_delete_post(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $author->id]);

        $this->actingAs($otherUser);

        $response = $this->delete(route('posts.destroy', $post));
        $response->assertStatus(302);
    }
}
