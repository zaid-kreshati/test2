<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function store(Request $request): JsonResponse
    {



        $post = $this->postService->createPost($request->all());


        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }

   public function update(Request $request, $id): JsonResponse
    {



        Log::info($request);

        $post = $this->postService->updatePost($id, $request);

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $this->postService->deletePost($id);

        return response()->json([
            'message' => 'Post deleted successfully'
        ], 200);
    }
}
