<?php

namespace App\Models\Blogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Blogs\Blogs_posts as Posts;
use App\Models\Blogs\Blogs_privots;


class Blogs_categories extends Model
{

    use HasFactory;




    public function scopeGetPosts(){

        return $this->belongsToMany(Blogs_posts::class,"blogs_privots");
        
    }

    
}
