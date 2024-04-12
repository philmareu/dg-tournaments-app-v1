<?php

namespace DGTournaments\Services\API\Contracts;


use Carbon\Carbon;
use DGTournaments\Services\API\Responses\TournamentsResponse;

interface TournamentApiInterface
{
    public function getTournamentsByRange(Carbon $from, Carbon $to) : TournamentsResponse;
}