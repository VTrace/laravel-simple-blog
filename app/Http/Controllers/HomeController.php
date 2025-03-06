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
        if (Auth::check()) {
            $limit = 15;
            $query = Post::query();
            $posts = $query->select(['title', 'status', 'slug', 'author_id', 'updated_at', 'published_at'])->with(['author:id,name'])->where('author_id', auth()->user()->id)->latest()->paginate($limit);
        } else {
            $posts = null;
        }

        return view('home', compact(
            'posts',
        ));
    }
}
