<?php

namespace DGTournaments\Models;

use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class StripeAccount extends Model
{
    protected $fillable = [
        'access_token',
        'display_name',
        'stripe_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
