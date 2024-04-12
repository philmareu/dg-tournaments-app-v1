<?php namespace DGTournaments\Services\Foursquare\Endpoints\Venues;

use DGTournaments\Services\Foursquare\Http\Get;
use DGTournaments\Services\Foursquare\Http\Url;

class Search extends Get
{
    public function aroundLatLng($latitude, $longitude, $radius = null)
    {
        $this->addParameter(['ll' => implode(',', [$latitude, $longitude])]);
        if($radius) $this->addParameter(['radius' => $radius]);

        return $this;
    }

    public function whereCategories($categories)
    {
        $categories = is_array($categories) ? implode(',', $categories) : $categories;

        $this->addParameter(['categoryId' => $categories]);

        return $this;
    }

    public function whereIntent($intent)
    {
        $this->addParameter(['intent' => $intent]);

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function get()
    {
        return $this->sendRequest(new Url('venues/search'));
    }
}