<?php

namespace DGTournaments\Models;

use DGTournaments\Events\LinkSaved;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'title',
        'url',
        'ordinal'
    ];

    protected $touches = [
        'tournament'
    ];

    protected $dispatchesEvents = [
        'created' => LinkSaved::class,
        'updated' => LinkSaved::class
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
