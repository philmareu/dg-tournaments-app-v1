<?php

namespace DGTournaments\Http\Controllers\Api;

use DGTournaments\Models\Tournament;
use DGTournaments\Repositories\TournamentRepository;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class WeatherController extends Controller
{
    protected $tournamentRepository;

    public function __construct(TournamentRepository $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    public function tournament(Tournament $tournament)
    {
        return $this->tournamentRepository->getWeather($tournament);
    }
}
