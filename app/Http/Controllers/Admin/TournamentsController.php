<?php

namespace DGTournaments\Http\Controllers\Admin;

use Carbon\Carbon;
use DGTournaments\Http\Requests\Admin\UpdateTournamentRequest;
use DGTournaments\Models\FlagType;
use DGTournaments\Models\Tournament;
use Illuminate\Http\Request;

use DGTournaments\Http\Requests;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\View\View;

class TournamentsController extends Controller
{
    protected $tournaments;
    /**
     * @var Tournament
     */
    private $tournament;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $tournaments = $this->tournament->with('courses')->get();

        return view('admin.tournaments.index')
            ->with('tournaments', $tournaments)
            ->withTitle('Tournaments');
    }

    public function future()
    {
        $tournaments = $this->tournament
            ->where('end', '>', Carbon::now()->subDay(1))
            ->orderBy('start')
            ->get();

        return view('admin.tournaments.index')
            ->with('tournaments', $tournaments)
            ->withTitle('Upcoming Tournaments');
    }

    public function flagged()
    {
        $tournaments = $this->tournament
            ->where('end', '>', Carbon::now()->subDay(1))
            ->whereHas('flags', function($query) {
                $query->where('review_on', '<', Carbon::now());
            })
            ->orderBy('start', 'desc')
            ->paginate(10);

        return view('admin.tournaments.flagged')
            ->with('tournaments', $tournaments);
    }

    public function show(Tournament $tournament)
    {
        return view('admin.tournaments.show')
            ->withTournament($tournament);
    }
}
