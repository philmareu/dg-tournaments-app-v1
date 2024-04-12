<?php

namespace DGTournaments\Models;

use DGTournaments\Models\Tournament;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    public $timestamps = false;

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class);
    }
}
