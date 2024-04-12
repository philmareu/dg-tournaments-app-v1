<?php namespace DGTournaments\Services\DarkSky\Http;

class Url
{
    /**
     * @var string
     */
    protected $baseUrl = 'https://api.darksky.net/forecast';

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