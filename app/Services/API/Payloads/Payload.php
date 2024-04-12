<?php

namespace DGTournaments\Services\API\Payloads;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use DGTournaments\Services\API\Exceptions\PayloadValuesInvalidException;

abstract class Payload extends Collection implements Arrayable
{
    protected $keys = [];

    public function __construct($items)
    {
        parent::__construct($items);
    }

    abstract protected function verifyPayload();
}