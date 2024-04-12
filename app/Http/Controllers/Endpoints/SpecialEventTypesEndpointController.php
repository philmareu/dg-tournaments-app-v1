<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Models\SpecialEventType;
use DGTournaments\Http\Controllers\Controller;

class SpecialEventTypesEndpointController extends Controller
{
    protected $specialEventType;

    public function __construct(SpecialEventType $specialEventType)
    {
        $this->specialEventType = $specialEventType;
    }

    public function list()
    {
        return $this->specialEventType->all();
    }
}
