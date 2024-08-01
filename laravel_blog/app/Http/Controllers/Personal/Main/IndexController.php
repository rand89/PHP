<?php

namespace App\Http\Controllers\Personal\Main;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        $data = [];
        $data['postsCount'] = auth()->user()->likedPosts->count();
        $data['commentsCount'] = auth()->user()->comments->count();
        return view('personal.main.index', compact('data'));
    }
}
