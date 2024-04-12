<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    protected $fillable = [
        'code',
        'title'
    ];

    public function tournament()
    {
        return $this->hasMany(Tournament::class);
    }
}
