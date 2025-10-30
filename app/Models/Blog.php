<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'thumbnail',
        'content',
        'special_role',
        'status',
        'views'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id')->withTrashed()->withDefault([
            'name' => 'Unknown Author'
        ]);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tags');
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('special_role', 2);
    }

    public function scopeTop($query)
    {
        return $query->orderByDesc('views');
    }

    public function scopeNewest($query)
    {
        return $query->latest();
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
