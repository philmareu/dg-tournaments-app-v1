<?php namespace DGTournaments\Services\Foursquare\Http;

class Url
{
    /**
     * @var string
     */
    protected $baseUrl = 'https://api.foursquare.com/v2';

    /**
     * @var string
     */
    protected $endPoint;

    public function __construct($endPoint)
    {
        $this->setEndPoint($endPoint);
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * @param string $endPoint
     */
    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function fullUrl()
    {
        return $this->baseUrl . '/' . $this->endPoint;
    }
}