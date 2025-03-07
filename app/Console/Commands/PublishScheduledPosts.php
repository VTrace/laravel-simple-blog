<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Enums\PostStatus;
use Illuminate\Support\Facades\DB;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';
    protected $description = 'Publish scheduled posts that have reached their scheduled time.';

    public function handle()
    {
        // Get scheduled posts that should be published
        $posts = Post::scheduleToPublish()->get();

        if ($posts->isEmpty()) {
            $this->info('No scheduled posts to publish.');
            return;
        }

        // Process publishing inside a transaction
        DB::transaction(function () use ($posts) {
            foreach ($posts as $post) {
                $post->update([
                    'status' => PostStatus::Published,
                    'published_at' => now(),
                ]);
                $this->info("Published: {$post->title}");
            }
        });

        $this->info('All scheduled posts have been published.');
    }
}