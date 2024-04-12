<?php

namespace DGTournaments\Http\Controllers\Endpoints\Tournament;

use DGTournaments\Models\Tournament;
use DGTournaments\Repositories\TournamentRepository;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class TournamentSurroundingCoursesEndpointController extends Controller
{
    protected $tournamentRepository;

    public function __construct(TournamentRepository $tournamentRepository)
    {
        $this->middleware('auth');
        $this->tournamentRepository = $tournamentRepository;
    }

    public function get(Tournament $tournament)
    {
        return $this->tournamentRepository->getSurroundingCourses($tournament);
    }
}
