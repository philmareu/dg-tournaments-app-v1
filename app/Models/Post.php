<?php

namespace DGTournaments\Models;

use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use PhilMareu\Laramanager\Models\LaramanagerImage;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body',
        'posted_at',
        'published',
        'category_id',
        'author_id',
        'image_id'
    ];

    protected $dates = [
        'posted_at'
    ];

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function image()
    {
        return $this->belongsTo(LaramanagerImage::class, 'image_id');
    }

    public function getPathAttribute()
    {
        return 'blog/' . $this->posted_at->format('Y/m/d') . '/' . $this->slug;
    }
}
