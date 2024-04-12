<?php namespace DGTournaments\Services\DarkSky;

use Carbon\Carbon;
use DGTournaments\Services\DarkSky\EndPoints\TimeMachine;

class DarkSkyApi
{
    public function getForecast($lat, $lng, Carbon $time)
    {
        $weather = new TimeMachine();

        try
        {
            $response = $weather->whereLatLng($lat, $lng)
                ->whereTime($time->timestamp)
                ->get();

            return collect($response['daily']['data'][0])->only([
                'time',
                'icon',
                'sunriseTime',
                'sunsetTime',
                'precipProbability',
                'precipType',
                'precipAccumulation',
                'temperatureMin',
                'temperatureMax',
                'apparentTemperatureMin',
                'apparentTemperatureMax',
                'humidity',
                'windSpeed',
                'windBearing',
                'cloudCover'
            ])->merge(['date' => $time->timestamp]);
        }
        catch (\Exception $exception)
        {
            return [];
        }
    }
}