<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = "blog_posts";
    protected $fillable = ['title', 'content'];

    use SoftDeletes;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Model Event
    public static function boot()
    {
        parent::boot();

        // static::deleting(function (BlogPost $blogpost) {
        //     $blogpost->comments()->delete();
        // });
    }
}
