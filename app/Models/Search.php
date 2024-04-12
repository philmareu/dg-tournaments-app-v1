<?php

namespace DGTournaments\Models;

use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $fillable = [
        'title',
        'query',
        'wants_notification',
        'searched_at',
        'frequency',
        'north',
        'east',
        'south',
        'west'
    ];

    protected $dates = [
        'searched_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
