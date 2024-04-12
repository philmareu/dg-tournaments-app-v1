<?php

namespace DGTournaments\Models;

use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sponsor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'url'
    ];

    public function logo()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
