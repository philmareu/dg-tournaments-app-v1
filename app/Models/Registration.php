<?php

namespace DGTournaments\Models;

use DGTournaments\Events\TournamentRegistrationUpdated;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'opens_at',
        'closes_at',
        'url'
    ];

    protected $dates = [
        'opens_at',
        'closes_at'
    ];

    protected $touches = [
        'tournament'
    ];

    protected $dispatchesEvents = [
        'created' => TournamentRegistrationUpdated::class,
        'updated' => TournamentRegistrationUpdated::class
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
