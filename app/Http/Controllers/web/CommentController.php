<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\Controller;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\JsonResponse;
use App\Models\Comment;

class CommentController extends Controller
{
    use JsonResponseTrait;
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index($postId)
    {
        $comments = $this->commentService->getPostComments($postId);
        $html = view('partials.comment', compact('comments'))->render();
        return $this->successResponse($html);
    }
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $comment=$this->commentService->store($request->all());
        Log::info("comment");
        Log::info($comment->parent);
        $personal_image=$comment->user->media->where('type', 'user_profile_image')->first();
        $name=$comment->user->name;
        $data=[
            'comment'=>$comment,
            'personal_image'=>$personal_image,
            'name'=>$name
        ];
        return $this->successResponse($data, 'Comment created successfully');
    }
    public function update(Request $request)
    {
        $this->commentService->update($request);
        return $this->successResponse(null, 'Comment updated successfully');
    }
    public function destroy(Request $request)
    {
        $this->commentService->destroy($request);
        return $this->successResponse(null, 'Comment deleted successfully');
    }

    public function storeNested(StoreCommentRequest $request)
    {
        Log::info("storeNested");
        Log::info($request->all());

        $comment = $this->commentService->storeNested($request->only('text', 'parent_id', 'post_id'));
        $personal_image = $comment->user->media->where('type', 'user_profile_image')->first();

        $name = $comment->user->name;
        $data = [
            'comment' => $comment,
            'personal_image' => $personal_image,
            'name' => $name
        ];
        return $this->successResponse($data, 'Comment created successfully');
    }

    public function getNestedComments(Request $request)
    {
        $parentId = $request->parent_id;
        $comment = Comment::find($parentId); // Add this line

        $nestedComments = $this->commentService->getNestedComments($parentId);
        Log::info("getNestedComments");
        Log::info($nestedComments);
        $html = view('partials.nested-comment', compact('nestedComments', 'comment'))->render();

        return $this->successResponse($html, 'Nested comments fetched successfully');
    }
}
