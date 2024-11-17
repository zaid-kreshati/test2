<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
       'owner_id', 'category_id', 'status', 'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


        public function category()
        {
            return $this->belongsTo(Category::class);
        }

        public function tag()
        {
            return $this->hasMany(Tag::class);
        }


        public function media()
        {
            return $this->morphMany(Media::class, 'mediable');
        }



        public function comment()
        {
            return $this->hasMany(Comment::class);
        }



}
