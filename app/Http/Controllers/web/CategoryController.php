<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class CategoryController extends Controller
{
    use JsonResponseTrait;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index2()
    {
        $categories = $this->categoryService->getParentNullCategories();
        $id=null;
        return view('DashBoard.categoryIndex', compact('categories', 'id'));
    }

    public function paginate(Request $request)
    {
        $categories = $this->categoryService->getCategoriesByParent($request->parent_id);
        $html = view('DashBoard.partials.categoryIndex', compact('categories'))->render();
        return $this->successResponse($html, __('Categories fetched successfully'));
    }

    public function store(Request $request)
    {
        $this->categoryService->createCategory($request->all());
        $categories = $this->categoryService->getCategoriesByParent($request->id);
        $html = view('DashBoard.partials.categoryIndex', compact('categories'))->render();
        return $this->successResponse($html, __('Category created successfully'));
    }

    public function update(Request $request, $id)
    {
        $this->categoryService->updateCategory($request, $id);
        return $this->successResponse(null, __('Category updated successfully'));
    }

    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return $this->successResponse(null, __('Category deleted successfully'));
    }
    public function search(Request $request)
    {
        $categories = $this->categoryService->searchCategories($request->search);
        $html = view('DashBoard.partials.categoryIndex', compact('categories'))->render();
        return $this->successResponse($html, __('Categories fetched successfully'));
    }
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'has_children' => $category->children->count() > 0 ? true : false
                ];
            });

        return $categories; // Return the collection directly, not wrapped in 'data'
    }

    public function getChildren($parentId)
    {
        $children=$this->categoryService->getChildren($parentId);
        Log::info('getChildren');
        Log::info($children);


        return $children; // Return the collection directly, not wrapped in 'data'
    }




}


