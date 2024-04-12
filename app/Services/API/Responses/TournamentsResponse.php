<?php

namespace DGTournaments\Services\API\Responses;

use DGTournaments\Services\API\Payloads\TournamentDataPayload;
use DGTournaments\Services\API\Exceptions\PayloadInvalidException;

class TournamentsResponse extends Response
{
    protected function verifyPayloads()
    {
        $this->payloads->each(function($payload) {
            if(! $payload instanceof TournamentDataPayload) throw new PayloadInvalidException();
        });
    }
}