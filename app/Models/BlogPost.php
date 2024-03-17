<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tag;

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

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    // Model Event
    public static function boot()
    {
        parent::boot();

        // This would delete the records permanently

        // static::deleting(function (BlogPost $blogpost) {
        //     $blogpost->comments()->delete();
        // });


        // Restore
        static::restoring(function (BlogPost $blogPost) {
            $blogPost->comments()->restore();
        });
    }
}
