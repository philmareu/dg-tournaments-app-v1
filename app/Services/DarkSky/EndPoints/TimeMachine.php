<?php namespace DGTournaments\Services\DarkSky\EndPoints;

use DGTournaments\Services\DarkSky\Http\Get;
use DGTournaments\Services\DarkSky\Http\Url;

class TimeMachine extends Get
{
    public function whereLatLng($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;

        return $this;
    }

    public function whereTime($time)
    {
        $this->time = $time;

        return $this;
    }

    public function exclude($block)
    {
        $this->addParameter(['exclude' => $block]);

        return $this;
    }

    public function getCurrent()
    {
        $this->addParameter(['exclude' => 'minutely,hourly,daily,flags']);

        $response = $this->get();

        return isset($response['currently']) ? $response['currently'] : null;
    }

    public function getDaily()
    {
        $this->addParameter(['exclude' => 'current,minutely,hourly,flags']);

        $response = $this->get();

        dd($response);

        return isset($response['currently']) ? $response['daily']['data'] : null;
    }

    public function get()
    {
        return $this->sendRequest(new Url(''));
    }
}