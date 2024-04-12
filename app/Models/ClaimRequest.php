<?php

namespace DGTournaments\Models;

use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimRequest extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'token';

    public $incrementing = false;

    protected $fillable = [
        'token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
