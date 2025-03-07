<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Actions\Posts\UpsertPostAction;
use App\Http\Requests\Posts\UpsertPostRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $limit = 15;
        $posts = Post::query()
            ->select(['title', 'slug', 'author_id', 'updated_at', 'published_at'])
            ->with(['author:id,name'])
            ->published()
            ->paginate($limit);

        return view('posts.index', compact(
            'posts',
        ));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post.
     */
    public function store(UpsertPostRequests $request, UpsertPostAction $upsertPostAction): RedirectResponse
    {
        $upsertPostAction->execute($request);

        return redirect()->route('home')->with('success', 'Post added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug): View
    {
        $post = Post::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertPostRequests $request, UpsertPostAction $upsertPostAction, Post $post)
    {
        $upsertPostAction->execute($request, $post);

        return redirect()->route('home')->with('success', 'Post modified successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully!');
    }
}
