<?php

namespace DGTournaments\Models;

use DGTournaments\Data\Price;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    public $fillable = [
        'destination',
        'fee',
        'amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function sponsorships()
    {
        return $this->hasMany(OrderSponsorship::class);
    }

    public function getTotalAttribute()
    {
        return Price::make($this->amount + $this->fee);
    }
}
