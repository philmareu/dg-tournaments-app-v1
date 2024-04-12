<?php

namespace DGTournaments\Models;

use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $table = 'request_log';

    protected $fillable = [
        'user_id',
        'ip',
        'method',
        'uri'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
