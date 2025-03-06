<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        $status = $this->faker->randomElement(['draft', 'scheduled', 'published']);
        $scheduledAt = $status === 'scheduled' ? $this->faker->dateTimeBetween('now', '+1 month') : null;
        $publishedAt = $status === 'published' ? $this->faker->dateTimeBetween('-1 month', 'now') : null;

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'body' => $this->faker->paragraphs(3, true),
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'published_at' => $publishedAt,
            'author_id' => User::factory(),
        ];
    }
}
