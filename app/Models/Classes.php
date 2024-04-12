<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    public $timestamps = false;

    protected $touches = [
        'tournaments'
    ];

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'class_tournament', 'class_id');
    }
}
