<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;

class ShowController extends Controller
{
    public function __invoke(Post $post)
    {
        $date = $post->created_at;
        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->get()
            ->take(4);
        return view('post.show', compact('post', 'date', 'relatedPosts'));
    }
}
