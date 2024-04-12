<?php

namespace DGTournaments\Models;

use DGTournaments\Events\TournamentCourseCreated;
use Illuminate\Database\Eloquent\Model;

class TournamentCourse extends Model
{
    protected $fillable = [
        'name',
        'holes',
        'latitude',
        'longitude',
        'address',
        'address_2',
        'city',
        'state_province',
        'country',
        'directions',
        'notes',
    ];

    protected $appends = [
        'hole_notes_array',
        'hole_array'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    protected $touches = [
        'tournament'
    ];

    protected $dispatchesEvents = [
        'created' => TournamentCourseCreated::class
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function original()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function getHoleNotesArrayAttribute()
    {
        return $this->holeNotes->groupBy('hole');
    }

    public function holeNotes()
    {
        return $this->hasMany(TournamentCourseHole::class)->orderBy('hole', 'asc');
    }

    public function getHoleArrayAttribute()
    {
        return range(1, $this->holes);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'resource')->latest();
    }
}
