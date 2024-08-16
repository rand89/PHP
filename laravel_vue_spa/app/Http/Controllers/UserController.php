<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StatRequest;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $service;
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->service->getUsers();
        return UserResource::collection($users);
    }

    public function post(User $user)
    {
        $posts = $this->service->getUserPosts($user);
        return PostResource::collection($posts);
    }

    public function toggleFollowing(User $user): array
    {
        return $this->service->toggleFollowing($user->id);
    }

    public function followedPost()
    {
        $posts = $this->service->getFollowedPosts();
        return PostResource::collection($posts);
    }

    public function stat(StatRequest $request)
    {
        $data = $request->validated();
        $result = $this->service->getStat($data);
        return response()->json(['data' => $result]);
    }
}
