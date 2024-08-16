<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\LikedPost;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function getPosts()
    {
        $posts = Post::where('user_id', auth()->id())->withCount('repostedByPosts')->latest()->get();
        return self::prepareLikedPosts($posts);
    }

    public function store($data)
    {
        try {
            DB::beginTransaction();
            $imageId = $data['image_id'];
            unset($data['image_id']);
            $data['user_id'] = auth()->id();
            $post = Post::create($data);
            $this->processImage($post, $imageId);
            PostImage::clearImages();
            DB::commit();
            return $post;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    private function processImage($post, $imageId)
    {
        if (isset($imageId)) {
            $image = PostImage::find($imageId);
            $image->update([
                'post_id' => $post->id
            ]);
        }
    }

    public function repost($data, $postId)
    {
        $data['user_id'] = auth()->id();
        $data['reposted_id'] = $postId;
        Post::create($data);
    }

    public function toggleLike(Post $post)
    {
        $result = auth()->user()->likedPosts()->toggle($post->id);
        $data['is_liked'] = count($result['attached']) > 0;
        $data['likes_count'] = $post->likedUsers()->count();
        return $data;
    }

    public function addComment($data, Post $post)
    {
        $data['user_id'] = auth()->id();
        $data['post_id'] = $post->id;
        return Comment::create($data);
    }

    public static function prepareLikedPosts($posts)
    {
        $postIds = LikedPost::where('user_id', auth()->id())
            ->get('post_id')->pluck('post_id')->toArray();

        foreach ($posts as $post) {
            if (in_array($post->id, $postIds)) {
                $post->is_liked = true;
            }
        }

        return $posts;
    }
}
