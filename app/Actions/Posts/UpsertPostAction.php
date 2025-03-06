<?php

namespace App\Actions\Posts;

use App\Models\Post;

class UpsertPostAction
{
    public function execute($request): Post
    {
        $status = 


        $post = Post::updateOrCreate(
            ['slug' => $request->slug],
            [
                'title' => $request->title,
                'body' => $request->body,
                'status' => ($request->is_draft ? 'draft' : ($request->scheduled_at > now() ? 'scheduled' : 'published' )),
                'is_draft' => $request->is_draft ?? false,
                'author_id' => auth()->user()->id,
                'scheduled_at' => $request->scheduled_at ?? null,
                'published_at' => $request->scheduled_at == now() ? $request->scheduled_at : null,
            ],
        );

        return $post;
    }
}
