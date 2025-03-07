<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            // Generate a title with a maximum length of 60 characters
            'title' => Str::limit($this->faker->sentence(10), 60, ''),

            // Generate a unique slug for the post
            'slug' => $this->faker->unique()->slug,

            // Generate random content for the post
            'body' => $this->faker->paragraphs(3, true),

            // Randomly assign a status from available options
            'status' => $this->faker->randomElement(['draft', 'published', 'scheduled']),

            // Generate a random published date or set it to null if not published
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 year', '+1 month'),

            // Associate the post with an existing or new user
            'author_id' => User::factory(),
        ];
    }

    /**
     * Define a state for published posts.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Define a state for draft posts.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Define a state for scheduled posts.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'published_at' => now()->addDays(3),
        ]);
    }
}
