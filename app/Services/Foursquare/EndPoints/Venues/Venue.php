<?php namespace DGTournaments\Services\Foursquare\Endpoints\Venues;

use DGTournaments\Services\Foursquare\Http\Get;
use DGTournaments\Services\Foursquare\Http\Url;

class Venue extends Get
{
    public function whereId($venueId)
    {
        return $this->sendRequest(new Url('venues/' . $venueId));
    }
}