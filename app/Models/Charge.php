<?php

namespace DGTournaments\Models;

use DGTournaments\Data\Price;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = [
        'charge_id',
        'status',
        'amount'
    ];

    public function getAmountAttribute($value)
    {
        return Price::make($value);
    }
}
