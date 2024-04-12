<?php namespace DGTournaments\Services\Pdga\EndPoints;

use DGTournaments\Services\Pdga\Http\Get;
use DGTournaments\Services\Pdga\Http\Url;

class Events extends Get 
{
    /**
     * @param string $name
     * @return $this
     */
    public function whereName($name)
    {
        $this->addParameter(['event_name' => $name]);

        return $this;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function whereState($state)
    {
        $this->addParameter(['state' => strtoupper($state)]);

        return $this;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function whereCountry($country)
    {
        $this->addParameter(['country' => $country]);

        return $this;
    }

    /**
     * Find events between these two dates. The
     * search will look only at the event
     * end dates to make this query.
     *
     * @param string (e.x. 2016-01-01) $from
     * @param string (e.x. 2016-07-01) $to
     * @return $this
     */
    public function betweenDates($from, $to)
    {
        $this->addParameter(['start_date' => $from]);
        $this->addParameter(['end_date' => $to]);

        return $this;
    }

    /**
     * This will select all events with an end_date
     * greater than the $date param
     *
     * @param string (e.x. 2016-01-01) $date
     * @return $this
     */
    public function fromDate($date)
    {
        $this->addParameter(['start_date' => $date]);

        return $this;
    }

    /**
     * If used without fromDate, this will only return
     * future events up to the $date param. Note
     * that the query only uses end_date.
     *
     * @param string (e.x. 2016-01-01) $date
     * @return $this
     */
    public function toDate($date)
    {
        $this->addParameter(['end_date' => $date]);

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
     * @return array|mixed
     */
    public function get()
    {
        $response = $this->sendRequest(new Url('event'));

        return isset($response['events']) ? $response['events']: [];
    }
}