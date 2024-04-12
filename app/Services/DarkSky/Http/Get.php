<?php namespace DGTournaments\Services\DarkSky\Http;

use DGTournaments\Models\ApiRequests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class Get
{
    use Query;

    protected $apiRate = 0;

    protected $latitude;

    protected $longitude;

    protected $time;

    /**
     * @return array|mixed
     */
    protected function sendRequest(Url $url)
    {
        $auth = new Auth();
        $client = new Client();

        $latLngTime = is_null($this->time) ? [$this->latitude, $this->longitude] : [$this->latitude, $this->longitude, $this->time];

        $segments = [
            $url->getBaseUrl(),
            $auth->getAuthorization()['secret'],
            implode(',', $latLngTime)
        ];

        try
        {
            $response = $client->get(implode('/', $segments));
            ApiRequests::create(['provider' => 'darksky', 'type' => implode('/', $segments)]);

            usleep($this->apiRate);

            return json_decode($response->getBody(), true);
        }
        catch (ClientException $clientException)
        {
            Log::info($clientException->getMessage());
        }

        return [];
    }
}