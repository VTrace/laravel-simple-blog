<?php

namespace App\Actions\Posts;

use Carbon\Carbon;
use App\Models\Post;

class UpsertPostAction
{
    public function execute($request): Post
    {
        return Post::updateOrCreate(
            ['slug' => $request->slug],
            [
                'title' => $request->title,
                'body' => $request->body,
                'status' => $this->determineStatus($request),
                'is_draft' => $request->boolean('is_draft'),
                'author_id' => auth()->id(),
                'scheduled_at' => $this->parseScheduledAt($request),
                'published_at' => $this->determinePublishedAt($request),
            ],
        );
    }

    /**
     * Determine post status based on request data.
     */
    private function determineStatus($request): string
    {
        if ($request->boolean('is_draft')) {
            return 'draft';
        }

        return $request->filled('scheduled_at') && Carbon::parse($request->scheduled_at)->isFuture()
            ? 'scheduled'
            : 'published';
    }

    /**
     * Parse scheduled_at date, ensuring it's properly formatted.
     */
    private function parseScheduledAt($request): ?Carbon
    {
        return $request->filled('scheduled_at')
            ? Carbon::parse($request->scheduled_at)
            : null;
    }

    /**
     * Determine published_at value based on scheduled date.
     */
    private function determinePublishedAt($request): ?Carbon
    {
        return $request->filled('scheduled_at') && Carbon::parse($request->scheduled_at)->isToday()
            ? Carbon::parse($request->scheduled_at)
            : null;
    }
}
