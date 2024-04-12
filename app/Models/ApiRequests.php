<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class ApiRequests extends Model
{
    protected $fillable = [
        'provider',
        'type'
    ];
}
