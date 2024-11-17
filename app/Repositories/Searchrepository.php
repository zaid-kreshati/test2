<?php

namespace App\Repositories;
use App\Models\User;
use App\Models\Post;

class SearchRepository
{
    public function searchAll($query)
    {
        $users = User::where('name', 'like', '%' . $query . '%')->with('media')->get();
        $posts = Post::where('description', 'like', '%' . $query . '%')->with('user.media','media')->get();

        return [
            'users' => $users,
            'posts' => $posts
        ];
    }

    public function searchAllPosts($query)
    {
        $posts = Post::where('description', 'like', '%' . $query . '%')->with('user.media','media')->get();
        return $posts;
    }

    public function searchPostswithphoto($query)
    {
        $posts = Post::whereHas('media', function($query) {
            $query->where('type', 'post_image');
        })->where('description', 'like', '%' . $query . '%')->with(['media', 'user.media'])->get();
        return $posts;
    }

    public function searchPostswithvideo($query)
    {
        $posts = Post::whereHas('media', function($query) {
            $query->where('type', 'post_video');
        })->where('description', 'like', '%' . $query . '%')->with(['media', 'user.media'])->get();
        return $posts;
    }

    public function searchUsers($query)
    {
        $users = User::where('name', 'like', '%' . $query . '%')->with('media')->get();
        return $users;
    }
}
