<?php namespace DGTournaments\Services\Foursquare\Http;

class Auth
{
    public function getAuthorization()
    {
        return [
            'client_id' => config('services.foursquare.clientId'),
            'client_secret' => config('services.foursquare.clientSecret')
        ];
    }
}