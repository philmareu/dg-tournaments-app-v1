<?php

namespace DGTournaments\Models;

use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'type',
        'data'
    ];

    public function resource()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }
}
