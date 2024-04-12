<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $fillable = [
        'title',
        'slug'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
