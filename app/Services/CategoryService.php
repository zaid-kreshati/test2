<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Log;


class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getParentNullCategories()
    {
        return $this->categoryRepository->getParentNullCategories();
    }

    public function getParentNullCategorieswithoutpagination()
    {
        return $this->categoryRepository->getParentNullCategorieswithoutpagination();
    }

    public function getCategoriesByParent($parent_id)
    {
        return $this->categoryRepository->getCategoriesByParent($parent_id);
    }

    public function createCategory(array $request)
    {
        return $this->categoryRepository->createCategory($request);
    }

    public function updateCategory($request, $id)
    {
        return $this->categoryRepository->updateCategory($request, $id);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepository->deleteCategory($id);
    }

    public function searchCategories($search)
    {
        return $this->categoryRepository->searchCategories($search);
    }

    public function getCategoriesByParentHtml($id)
    {
        return $this->categoryRepository->getCategoriesByParentHtml($id);
    }

    public function getChildren($parentId)
    {
        return $this->categoryRepository->getChildren($parentId);
    }

}

