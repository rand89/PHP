<?php

namespace App\Services;

use App\Http\Requests\PostImage\StoreRequest;
use App\Models\PostImage;
use Illuminate\Support\Facades\Storage;

class PostImageService
{
    public function store(StoreRequest $request)
    {
        $path = Storage::disk('public')->put('/images', $request['image']);
        return PostImage::create([
            'path' => $path,
            'user_id' => auth()->id()
        ]);
    }
}
