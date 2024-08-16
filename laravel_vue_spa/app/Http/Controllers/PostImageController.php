<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostImage\StoreRequest;
use App\Http\Resources\PostImage\PostImageResource;
use App\Services\PostImageService;

class PostImageController extends Controller
{
    protected PostImageService $service;
    public function __construct(PostImageService $service)
    {
        $this->service = $service;
    }

    public function store(StoreRequest $request)
    {
        $img = $this->service->store($request);
        return new PostImageResource($img);
    }
}
