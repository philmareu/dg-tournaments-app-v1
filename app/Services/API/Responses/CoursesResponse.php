<?php

namespace DGTournaments\Services\API\Responses;


use DGTournaments\Services\API\Exceptions\PayloadInvalidException;
use DGTournaments\Services\API\Payloads\CourseDataPayload;

class CoursesResponse extends Response
{
    protected function verifyPayloads()
    {
        $this->payloads->each(function($payload) {
            if(! $payload instanceof CourseDataPayload) throw new PayloadInvalidException();
        });
    }
}