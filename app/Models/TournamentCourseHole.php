<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentCourseHole extends Model
{
    protected $fillable = [
        'hole',
        'notes'
    ];

    protected $touches = [
        'tournamentCourse'
    ];

    public function tournamentCourse()
    {
        return $this->belongsTo(TournamentCourse::class);
    }
}
