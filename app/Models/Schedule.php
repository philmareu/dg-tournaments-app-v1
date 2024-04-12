<?php

namespace DGTournaments\Models;

use DGTournaments\Collections\ScheduleCollection;
use DGTournaments\Events\ScheduleSaved;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'date',
        'start',
        'end',
        'summary',
        'location'
    ];

    protected $appends = [
        'time_span'
    ];

    protected $dates = [
        'date',
        'start',
        'end'
    ];

    protected $touches = [
        'tournament'
    ];

    protected $dispatchesEvents = [
        'created' => ScheduleSaved::class,
        'updated' => ScheduleSaved::class
    ];

    public function newCollection(array $models = [])
    {
        return new ScheduleCollection($models);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function getTimeSpanAttribute()
    {
        if(is_null($this->start)) return 'TBA';

        $format = 'g:ia';

        if(is_null($this->end)) return $this->start->format($format);

        if($this->start->format('A') === $this->end->format('A'))
            return $this->start->format('g:i') . ' - ' . $this->end->format('g:i') . $this->end->format('a');

        return $this->start->format($format) . ' - ' . $this->end->format($format);
    }
}
