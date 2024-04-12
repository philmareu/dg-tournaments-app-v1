<?php namespace DGTournaments\Services\Foursquare\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Get
{
    use Query;

    protected $apiRate = 0;

    /**
     * @return array|mixed
     */
    protected function sendRequest(Url $url)
    {
        $auth = new Auth();
        $client = new Client();

        try
        {
            $response = $client->get($url->fullUrl(), [
                'query' => array_merge(
                    $this->getParameters(),
                    $auth->getAuthorization(),
                    [
                        'v' => '20170106',
                        'm' => 'foursquare',
                        'limit' => 20
                    ]
                )
            ]);

            usleep($this->apiRate);
            return $response->json()['response'];
        }
        catch (ClientException $clientException)
        {
            dump($clientException->getMessage());
            dump($clientException->getResponse()->getBody()->getContents());
        }

        return [];
    }
}