<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $limit = 15;

        // Get posts only if authenticated
        $posts = Auth::check()
            ? Post::select(['title', 'status', 'slug', 'author_id', 'updated_at', 'published_at'])
                ->with('author:id,name')
                ->where('author_id', Auth::id())
                ->latest()
                ->paginate($limit)
            : collect(); // Return empty collection for guests

        return view('home', compact('posts'));
    }
}
