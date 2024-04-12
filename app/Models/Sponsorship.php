<?php

namespace DGTournaments\Models;

use DGTournaments\Data\Price;
use DGTournaments\Events\Models\SponsorshipCreated;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    protected $fillable = [
        'title',
        'tier',
        'quantity',
        'cost',
        'description'
    ];

    protected $appends = [
        'cost_in_dollars'
    ];

    protected $touches = [
        'tournament'
    ];

    protected $dispatchesEvents = [
        'created' => SponsorshipCreated::class
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function tournamentSponsors()
    {
        return $this->hasMany(TournamentSponsor::class);
    }

    public function getCostAttribute($value)
    {
        return new Price($value);
    }

    public function setCostAttribute($value)
    {
        $currency = new Price($value, 'dollars');

        $this->attributes['cost'] = $currency->inCents();
    }

    public function getCostInDollarsAttribute()
    {
        return $this->cost->inDollars();
    }
}
