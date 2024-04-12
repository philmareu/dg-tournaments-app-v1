<?php

namespace DGTournaments\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserPaymentMethod extends Model
{
    protected $fillable = [
        'card',
        'brand',
        'expiration_month',
        'expiration_year',
        'last_4'
    ];
}
