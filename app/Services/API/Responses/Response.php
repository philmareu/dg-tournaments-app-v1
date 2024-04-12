<?php

namespace DGTournaments\Services\API\Responses;

use Illuminate\Support\Collection;

abstract class Response
{
    /**
     * @var integer
     */
    protected $status;

    /**
     * @var Collection
     */
    protected $payloads;

    /**
     * ListingsResponse constructor.
     * @param $status
     * @param Collection $payloads
     */
    public function __construct($status, Collection $payloads = null)
    {
        $this->status = $status;
        $this->payloads = $payloads;
        $this->verifyPayloads();
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function oK($status = 200)
    {
        return $this->status == $status;
    }

    public function getPayloads()
    {
        return $this->payloads;
    }

    protected function verifyPayloads()
    {

    }
}