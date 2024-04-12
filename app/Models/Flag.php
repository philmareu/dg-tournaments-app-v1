<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    protected $fillable = [
        'tournament_id',
        'flag_type_id',
        'notes',
        'review_on'
    ];

    protected $dates = [
        'review_on'
    ];

    public function flagType()
    {
        return $this->belongsTo(FlagType::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
