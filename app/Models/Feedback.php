<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'email',
        'feedback'
    ];
}
