<?php

namespace DGTournaments\Models;

use DGTournaments\Events\TournamentFollowed;
use DGTournaments\Events\TournamentUnfollowed;
use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $dispatchesEvents = [
        'created' => TournamentFollowed::class,
        'deleting' => TournamentUnfollowed::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resource()
    {
        return $this->morphTo();
    }
}
