<?php namespace DGTournaments\Services\Pdga\Http;

use Carbon\Carbon;
use GuzzleHttp\Client;

class Auth {

    protected $url;

    protected $username;

    protected $password;

    public function __construct(Url $url)
    {
        $this->username = config('services.pdga.username');
        $this->password = config('services.pdga.secret');
        $this->url = $url;
    }

    public function getAuthorization()
    {
        $client = new Client();

        $response = $client->post($this->url->getBaseUrl() . '/user/login', [
            'json' => [
                'username' => $this->username,
                'password' => $this->password
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}