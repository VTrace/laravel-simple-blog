<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Enums\PostStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a post's author can update their own post.
     */
    public function test_post_author_can_update_their_own_post(): void
    {
        // Create a user and their post
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'author_id' => $user->id,
            'title' => 'Original Title',
            'body' => 'Original Body',
            'status' => PostStatus::Draft->value,
            'scheduled_at' => now()->addDays(2),
        ]);

        // Attempt to update the post as the owner
        $response = $this->actingAs($user)->put(route('posts.update', $post->slug), [
            'slug' => $post->slug,
            'title' => 'Updated Title',
            'body' => 'Updated Body',
            'scheduled_at' => now()->addDays(5)->format('Y-m-d'),
        ]);

        // Ensure the response redirects to home
        $response->assertRedirect(route('home'));

        // Ensure the database has the updated post
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'body' => 'Updated Body',
            'scheduled_at' => now()->addDays(5)->toDateString(), // Convert to expected string format
        ]);
    }

    /**
     * Test that a post's author can delete their own post.
     */
    public function test_post_author_can_delete_their_own_post(): void
    {
        // Create a user and their post
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'author_id' => $user->id,
        ]);

        // Attempt to delete the post as the owner
        $response = $this->actingAs($user)->delete(route('posts.destroy', $post->slug));

        // Ensure the response redirects to home
        $response->assertRedirect(route('home'));

        // Ensure the post is deleted from the database
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /**
     * Test that a non-author cannot update someone else's post.
     */
    public function test_non_author_cannot_update_someone_elses_post(): void
    {
        // Create two users
        $author = User::factory()->create();
        $otherUser = User::factory()->create();

        // Create a post belonging to the author
        $post = Post::factory()->create([
            'author_id' => $author->id,
            'title' => 'Original Title',
            'body' => 'Original Body',
        ]);

        // Attempt to update the post as a different user
        $response = $this->actingAs($otherUser)->put(route('posts.update', $post->slug), [
            'title' => 'Hacked Title',
            'body' => 'Hacked Body',
        ]);

        // Ensure the response is forbidden (403)
        $response->assertForbidden();

        // Ensure the database still has the original data
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Original Title',
            'body' => 'Original Body',
        ]);
    }

    /**
     * Test that a non-author cannot delete someone else's post.
     */
    public function test_non_author_cannot_delete_someone_elses_post(): void
    {
        // Create two users
        $author = User::factory()->create();
        $otherUser = User::factory()->create();

        // Create a post belonging to the author
        $post = Post::factory()->create([
            'author_id' => $author->id,
        ]);

        // Attempt to delete the post as a different user
        $response = $this->actingAs($otherUser)->delete(route('posts.destroy', $post->slug));

        // Ensure the response is forbidden (403)
        $response->assertForbidden();

        // Ensure the post still exists in the database
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
