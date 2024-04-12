<?php namespace DGTournaments\Services\DarkSky\Http;

class Auth
{
    public function getAuthorization()
    {
        return [
            'secret' => config('services.darksky.secret')
        ];
    }
}