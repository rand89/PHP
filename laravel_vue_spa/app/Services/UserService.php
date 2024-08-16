<?php

namespace App\Services;

use App\Models\LikedPost;
use App\Models\Post;
use App\Models\SubscriberFollowing;
use App\Models\User;

class UserService
{
    public function getUsers()
    {
        $users = User::whereNot('id', auth()->id())->get();
        $followingIds = SubscriberFollowing::where('subscriber_id', auth()->id())
            ->get('following_id')->pluck('following_id')->toArray();

        foreach ($users as $user) {
            if (in_array($user->id, $followingIds)) {
                $user->is_followed = true;
            }
        }

        return $users;
    }

    public function toggleFollowing($userId)
    {
        $result = auth()->user()->followings()->toggle($userId);
        $data['is_followed'] = count($result['attached']) > 0;
        return $data;
    }

    public function getFollowedPosts()
    {
        $followedIds = auth()->user()->followings()->get()->pluck('id')->toArray();

        $postIds = LikedPost::where('user_id', auth()->id())
            ->get('post_id')->pluck('post_id')->toArray();

        $posts = Post::whereIn('user_id', $followedIds)
            ->whereNotIn('id', $postIds)
            ->withCount('repostedByPosts')
            ->latest()->get();

        return $posts;
    }

    public function getStat($data): array
    {
        $userId = $data['user_id'] ?? auth()->id();
        $result = [];
        $result['subscribers_count'] = SubscriberFollowing::where('following_id', $userId)->count();
        $result['followings_count'] = SubscriberFollowing::where('subscriber_id', $userId)->count();
        $postIds = Post::where('user_id', $userId)->get('id')->pluck('id')->toArray();
        $result['likes_count'] = LikedPost::whereIn('post_id', $postIds)->count();
        $result['posts_count'] = count($postIds);

        return $result;
    }

    public function getUserPosts(User $user)
    {
        $posts = $user->posts()->withCount('repostedByPosts')->latest()->get();
        return PostService::prepareLikedPosts($posts);
    }
}
