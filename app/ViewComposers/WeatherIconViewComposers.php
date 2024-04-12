<?php namespace DGTournaments\ViewComposers;

use Illuminate\View\View;

class WeatherIconViewComposers
{
    public function compose(View $view)
    {
        $weatherIcons = [
            'clear-day' => 'wi-day-sunny',
            'clear-night' => 'wi-night-clear',
            'rain' => 'wi-rain',
            'snow' => 'wi-snow',
            'sleet' => 'wi-sleet',
            'wind' => 'wi-cloudy-windy',
            'fog' => 'wi-fog',
            'cloudy' => 'wi-cloudy',
            'partly-cloudy-day' => 'wi-day-cloudy',
            'partly-cloudy-night' => 'wi-night-partly-cloudy'
        ];

        $view->with('weatherIcons', $weatherIcons);
    }
}