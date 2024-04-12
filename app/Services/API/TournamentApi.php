<?php

namespace DGTournaments\Services\API;


use Carbon\Carbon;
use DGTournaments\Models\DataSource;
use DGTournaments\Services\API\Contracts\CourseApiInterface;
use DGTournaments\Services\API\Contracts\TournamentApiInterface;
use DGTournaments\Services\API\Responses\CoursesResponse;
use DGTournaments\Services\API\Responses\TournamentsResponse;

class TournamentApi implements TournamentApiInterface
{
    protected $channelApi;

    public function __construct(DataSource $dataSource)
    {
        $apiClass = $dataSource->api_class;
        $this->channelApi = new $apiClass;
    }

    static public function make(DataSource $dataSource)
    {
        return new static($dataSource);
    }

    public function getTournamentsByRange(Carbon $from, Carbon $to) : TournamentsResponse
    {
        return $this->channelApi->getTournamentsByRange($from, $to);
    }
}