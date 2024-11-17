<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\Controller;
use Illuminate\Http\Request;
use App\Services\SearchService;
use App\Traits\JsonResponseTrait;

class SearchController extends Controller
{
    use JsonResponseTrait;
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function searchAll(Request $request)
    {
        $query = $request->input('query');
        $resaults = $this->searchService->searchAll($query);
        //return $resaults;
        return $this->successResponse($resaults, 'Search results fetched successfully');
    }

    public function searchAllPosts(Request $request)
    {
        $query = $request->input('query');
        $resaults = $this->searchService->searchAllPosts($query);
        $data = [
            'posts' => $resaults
        ];
        return $this->successResponse($data, 'Search results fetched successfully');
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        $users = $this->searchService->searchUsers($query);
        $data = [
            'users' => $users
        ];
        return $this->successResponse($data, 'Search results fetched successfully');
    }

    public function searchPostswithphoto(Request $request)
    {
        $query = $request->input('query');
        $resaults = $this->searchService->searchPostswithphoto($query);
        $data = [
            'posts' => $resaults
        ];
        return $this->successResponse($data, 'Search results fetched successfully');
    }

    public function searchPostswithvideo(Request $request)
    {
        $query = $request->input('query');
        $resaults = $this->searchService->searchPostswithvideo($query);
        $data = [
            'posts' => $resaults
        ];
        return $this->successResponse($data, 'Search results fetched successfully');
    }
}
