<?php namespace DGTournaments\Services\Foursquare;

use DGTournaments\Services\Foursquare\Endpoints\Venues\Search;
use DGTournaments\Services\Foursquare\Endpoints\Venues\Venue;

class FoursquareApi
{
    public function getVenues($geo, $category)
    {
        $api = new Search;

        return $api->aroundLatLng($geo[0], $geo[1], $geo[2])
            ->whereCategories($category)
            ->whereIntent('browse')
            ->get();
    }

    public function getVenue($venueId)
    {
        $api = new Venue;

        return $api->whereId($venueId)['venue'];
    }
}