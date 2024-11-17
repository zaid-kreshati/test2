<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // Create parent comments
        $comment1 = Comment::create([
            'parent_id' => null, // No parent, it's a top-level comment
            'user_id' => '1',
            'text' => 'This is a top-level comment from User One.',
        ]);

        $comment2 = Comment::create([
            'parent_id' => null, // No parent, it's another top-level comment
            'user_id' => '2',
            'text' => 'This is a top-level comment from User Two.',
        ]);

        // Create nested comments (children)
        Comment::create([
            'parent_id' => $comment1->id, // Child of the first comment
            'user_id' => $comment1->user_id,
            'text' => 'This is a reply to User One\'s comment.',
        ]);

        Comment::create([
            'parent_id' => $comment1->id, // Another child of the first comment
            'user_id' => $comment1->user_id,
            'text' => 'This is another reply to User One\'s comment.',
        ]);

        Comment::create([
            'parent_id' => $comment2->id, // Child of the second comment
            'user_id' => $comment2->user_id,
            'text' => 'This is a reply to User Two\'s comment.',
        ]);
    }
}
