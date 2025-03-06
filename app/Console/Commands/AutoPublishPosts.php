<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Enums\PostStatus;
use Carbon\Carbon;

class AutoPublishPosts extends Command
{
    protected $signature = 'posts:auto-publish';
    protected $description = 'Automatically publish scheduled posts';

    public function handle()
    {
        $updated = Post::scheduleToPublish()->update(['status' => PostStatus::Published, 'published_at' => Carbon::now()->format('Y/m/d')]);

        $this->info("$updated posts have been published.");
    }
}
