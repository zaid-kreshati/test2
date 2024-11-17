<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'user_id',
        'text',
        'post_id',


     ];

     public function parent()
     {
         return $this->belongsTo(Comment::class, 'parent_id');
     }


     public function user()
     {
         return $this->belongsTo(User::class, 'user_id');
     }

     public function post()
     {
         return $this->belongsTo(Post::class, 'post_id');
     }

 




}
