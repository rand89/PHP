<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CommentRequest;
use App\Http\Requests\Post\RepostRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Services\PostService;

class PostController extends Controller
{
    protected PostService $service;
    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $posts = $this->service->getPosts();
        return PostResource::collection($posts);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        try {
            $post = $this->service->store($data);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }

        return new PostResource($post);
    }

    public function repost(RepostRequest $request, Post $post)
    {
        $data = $request->validated();
        $this->service->repost($data, $post->id);
    }

    public function toggleLike(Post $post)
    {
        return $this->service->toggleLike($post);
    }

    public function comment(Post $post, CommentRequest $request)
    {
        $data = $request->validated();
        $comment = $this->service->addComment($data, $post);
        return new CommentResource($comment);
    }

    public function commentsList(Post $post)
    {
        $comments = $post->comments()->get();
        return CommentResource::collection($comments);
    }
}
