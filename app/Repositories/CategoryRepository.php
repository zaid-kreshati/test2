<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryRepository
{
    public function getParentNullCategories()
    {
        return Category::whereNull('parent_id')->orderBy('id', 'desc')->paginate(5);

    }

    public function getParentNullCategorieswithoutpagination()
    {
        return Category::whereNull('parent_id')->orderBy('id', 'desc')->get();

    }

    public function getCategoriesByParent($parent_id)
    {
        return Category::where('parent_id', $parent_id)->orderBy('id', 'desc')->paginate(5);

    }

    public function createCategory(array $request)
    {
        if(isset($request['id']) && is_null($request['id']))
          $Category=Category::create([
            'name' => $request['name'],
        ]);
        else{

        $Category=Category::create([
            'name' => $request['name'],
            'parent_id' => $request['id'],
        ]);
    }

    Log::info($Category);

        return;
    }




    public function updateCategory($request, $id)
    {
        return Category::find($id)->update($request->all());
    }

    public function deleteCategory($id)
    {
        return Category::find($id)->delete();
    }

    public function searchCategories($search)
    {
        return Category::where('name', 'like', '%' . $search . '%')->orderBy('id','desc')->paginate(5);
    }

    public function getCategoriesByParentHtml($id)
    {
        return Category::where('parent_id', $id)->orderBy('id', 'desc')->get();
    }

    public function getChildren($parentId)
    {
        return Category::where('parent_id', $parentId)->orderBy('id', 'desc')->get();
    }

}
