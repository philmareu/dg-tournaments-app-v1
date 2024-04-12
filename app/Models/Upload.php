<?php

namespace DGTournaments\Models;

use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'filename',
        'title',
        'size',
        'alt',
        'mime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
