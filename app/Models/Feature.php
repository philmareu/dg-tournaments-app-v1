<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;
use PhilMareu\Laramanager\Models\LaramanagerImage;

class Feature extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_id',
        'video_url',
        'ordinal',
        'category'
    ];

    public function image()
    {
        return $this->belongsTo(LaramanagerImage::class, 'image_id');
    }
}
