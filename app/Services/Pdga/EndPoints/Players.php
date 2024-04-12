<?php namespace DGTournaments\Services\Pdga\EndPoints;

use DGTournaments\Services\Pdga\Http\Get;
use DGTournaments\Services\Pdga\Http\Url;

class Players extends Get 
{
    /**
     * @param string $firstName
     */
    public function whereFirstName($firstName)
    {
        $this->addParameter(['first_name' => $firstName]);

        return $this;
    }

    /**
     * @param string $lastName
     */
    public function whereLastName($lastName)
    {
        $this->addParameter(['first_name' => $lastName]);

        return $this;
    }

    /**
     * @param string $city
     * @param string $state
     * @return $this
     */
    public function whereCity($city, $state)
    {
        $this->addParameter(['city' => $city, 'state_prov' => strtoupper($state)]);

        return $this;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function whereState($state)
    {
        $this->addParameter(['state_prov' => strtoupper($state)]);

        return $this;
    }

    /**
     * @param string $county
     * @return $this
     */
    public function whereCountry($county)
    {
        $this->addParameter(['country' => $county]);

        return $this;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function whereClass($class)
    {
        $this->addParameter(['class' => $class]);

        return $this;
    }

    /**
     * @param int $pdgaNumber
     * @return mixed
     */
    public function getByPdgaNumber($pdgaNumber)
    {
        $this->addParameter(['pdga_number' => $pdgaNumber]);

        $response = $this->sendRequest(new Url('players'));

        return isset($response['players'][0]) ? $response['players'][0] : null;
    }

    /**
     * Call API and return the courses
     *
     * @return array|mixed
     */
    public function get()
    {
        $response = $this->sendRequest(new Url('players'));

        return isset($response['players']) ? $response['players']: [];
    }
}