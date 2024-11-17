<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getPostComments($postId)
    {
        return $this->commentRepository->getPostComments($postId);
    }

    public function store(array $request)
    {
        $comment = $this->commentRepository->store($request);
        return $comment;
    }

    public function update(Request $request)
    {
        $this->commentRepository->update($request);
    }

    public function destroy(Request $request)
    {
        $this->commentRepository->destroy($request);
    }

    public function storeNested(array $request)
    {
        return $this->commentRepository->storeNested($request);
    }

    public function getNestedComments($parentId)
    {
        return $this->commentRepository->getNestedComments($parentId);
    }
}
