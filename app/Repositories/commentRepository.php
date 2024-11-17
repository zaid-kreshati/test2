<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentRepository
{
    public function getPostComments($postId)
    {
        $comments = Comment::where('post_id', $postId)->where('parent_id', null)->with('post')->get();
        Log::info("comment10");
        Log::info($comments);
        return $comments;
    }

    public function store(array $request)
    {
        $user_id = Auth::id();
        $request['user_id'] = $user_id;
        $comment = Comment::create($request);

        return $comment;
    }

    public function update(Request $request)
    {
        Comment::find($request->id)->update($request->all());
    }

    public function destroy(Request $request)
    {
        Comment::find($request->id)->delete();
    }

    public function storeNested(array $request)
    {
        $user_id = Auth::id();
        $request['user_id'] = $user_id;
        Log::info($request);
        return Comment::create($request);
    }

    public function getNestedComments($parentId)
    {
        return Comment::where('parent_id', '!=', null)->where('parent_id', $parentId)->get();
    }
}
