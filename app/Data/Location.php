<?php

namespace DGTournaments\Data;


use Illuminate\Contracts\Support\Arrayable;

class Location implements Arrayable
{
    protected $city;

    protected $state;

    protected $country;

    public function __construct($city, $country, $state = null)
    {
        $this->city = $city;
        $this->country = $country;
        $this->state = $state;
    }

    static public function make($city, $country, $state = null)
    {
        return new static($city, $country, $state);
    }

    public function formatted()
    {
        $cityState = (is_null($this->state)) ? $this->city : $this->city . ', ' . $this->state;

        return "$cityState ({$this->country})";
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->formatted();
    }
}