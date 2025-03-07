<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Actions\Posts\UpsertPostAction;
use App\Http\Requests\Posts\UpsertPostRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = 15;
        $query = Post::query();
        $posts = $query->select(['title', 'slug', 'author_id', 'updated_at', 'published_at'])->with(['author:id,name'])->published()->paginate($limit);
        $count = $posts->count();
        $count_all = $query->count();

        return view('posts.index', compact(
            'posts',
            'count',
            'count_all',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertPostRequests $request, UpsertPostAction $upsertPostAction)
    {
        $upsertPostAction->execute($request);

        return redirect()->route('home')->with('success', 'Post added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
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
