<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentSponsor extends Model
{
    protected $touches = [
        'tournament'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function sponsorship()
    {
        return $this->belongsTo(Sponsorship::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class)->withTrashed();
    }
}
