<?php

namespace App\Services;
use App\Repositories\SearchRepository;

class SearchService
{
    protected $searchRepository;

    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function searchAll($query)
    {
        return $this->searchRepository->searchAll($query);
    }

    public function searchPostswithphoto($query)
    {
        return $this->searchRepository->searchPostswithphoto($query);
    }

    public function searchPostswithvideo($query)
    {
        return $this->searchRepository->searchPostswithvideo($query);
    }

    public function searchAllPosts($query)
    {
        return $this->searchRepository->searchAllPosts($query);
    }

    public function searchUsers($query)
    {
        return $this->searchRepository->searchUsers($query);
    }
}
