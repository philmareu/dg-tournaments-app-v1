<?php

namespace DGTournaments\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserReferral extends Model
{
    protected $fillable = [
        'email'
    ];

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }
}
