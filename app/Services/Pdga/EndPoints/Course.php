<?php

namespace DGTournaments\Services\Pdga\EndPoints;


use DGTournaments\Services\Pdga\Http\Get;
use DGTournaments\Services\Pdga\Http\Url;

class Course extends Get
{
    /**
     * @param string $country
     */
    public function whereCountry($firstName)
    {
        $this->addParameter(['country' => $firstName]);

        return $this;
    }

    /**
     * Set API limit
     *
     * @param int $limit
     */
    public function limit($limit)
    {
        $this->addParameter(['limit' => $limit]);

        return $this;
    }

    /**
     * Set API offset
     *
     * @param int $offset
     */
    public function offset($offset)
    {
        $this->addParameter(['offset' => $offset]);

        return $this;
    }

    /**
     * Call API and return the courses
     *
     * @return array|mixed
     */
    public function get()
    {
        $response = $this->sendRequest(new Url('course'));

        return isset($response['courses']) ? $response['courses']: [];
    }
}