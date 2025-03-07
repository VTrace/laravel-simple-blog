<?php

namespace App\Actions\Posts;

use Carbon\Carbon;
use App\Models\Post;

class UpsertPostAction
{
    public function execute(array $data): Post
    {
        $post = Post::updateOrCreate(
            ['slug' => $data['slug'] ?? null],
            [
                'title' => $data['title'],
                'body' => $data['body'],
                'status' => $this->determineStatus($data),
                'is_draft' => data_get($data, 'is_draft', false),
                'author_id' => auth()->id(),
                'scheduled_at' => data_get($data, 'scheduled_at'),
                'published_at' => $this->determinePublishedAt($data),
            ]
        );

        return $post;
    }

    /**
     * Determine post status based on input data.
     */
    private function determineStatus(array $data): string
    {
        if (!empty($data['is_draft'])) {
            return 'draft';
        }

        $scheduledAt = data_get($data, 'scheduled_at');
        return $scheduledAt && $scheduledAt > now() ? 'scheduled' : 'published';
    }

    /**
     * Determine published_at value.
     */
    private function determinePublishedAt(array $data): ?string
    {
        $scheduledAt = data_get($data, 'scheduled_at');

        // If scheduled_at is exactly now, set it as published
        if ($scheduledAt && now()->equalTo(Carbon::parse($scheduledAt))) {
            return $scheduledAt;
        }

        return null;
    }
}
