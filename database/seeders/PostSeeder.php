<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Photo;
use App\Models\Video;
use App\Models\Comment;
use App\Models\Tag;
class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::first();
        $users = User::take(2)->get();

        foreach ($users as $user) {
            // Create published posts
            Post::factory()
                ->count(5)
                ->state([
                    'owner_id' => $user->id,
                    'category_id' => $category->id,
                    'status' => 'published'
                ])
                ->create();



            // Create draft posts
            Post::factory()
                ->count(5)
                ->state([
                    'owner_id' => $user->id,
                    'category_id' => $category->id,
                    'status' => 'draft'
                ])
                ->create();

            // Create archived posts
            Post::factory()
                ->count(5)
                ->state([
                    'owner_id' => $user->id,
                    'category_id' => $category->id,
                    'status' => 'archived'
                ])
                ->create();

                $i = 1;
                for($i; $i < 16; $i++ ){
                Tag::create([
                    'user_id' => $user->id,
                    'post_id' => $i,
                ]);
                }


        }


    }
}
