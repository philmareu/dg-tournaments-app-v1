<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'uri',
        'description',
        'body'
    ];
}
