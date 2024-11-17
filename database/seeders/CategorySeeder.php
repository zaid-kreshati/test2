<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent categories
        $parentCategory1 = Category::create([
            'name' => 'public',
            'parent_id' => null,
        ]);

        $parentCategory2 = Category::create([
            'name' => 'social',
            'parent_id' => null,
        ]);

        // Create child categories and associate with parent
        Category::create([
            'name' => 'buisness',
            'parent_id' => $parentCategory1->id,
        ]);

        Category::create([
            'name' => 'politic',
            'parent_id' => $parentCategory1->id,
        ]);

        Category::create([
            'name' => 'friends',
            'parent_id' => $parentCategory2->id,
        ]);
    }
}
