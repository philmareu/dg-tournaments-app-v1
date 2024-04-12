<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class PdgaTier extends Model
{
    protected $fillable = [
        'code',
        'title'
    ];

    public function tournament()
    {
        return $this->belongsToMany(Tournament::class);
    }
}
