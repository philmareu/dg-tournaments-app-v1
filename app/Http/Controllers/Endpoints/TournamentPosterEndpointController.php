<?php

namespace DGTournaments\Http\Controllers\Endpoints\Tournament;

use DGTournaments\Http\Requests\Endpoints\Tournament\DestroyTournamentPosterRequest;
use DGTournaments\Http\Requests\UpdateTournamentPosterRequest;
use DGTournaments\Models\Tournament;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class TournamentPosterEndpointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(UpdateTournamentPosterRequest $request, Tournament $tournament)
    {
        $tournament->update(['poster_id' => $request->upload_id]);

        return $tournament->load('poster')->poster;
    }

    public function destroy(DestroyTournamentPosterRequest $request, Tournament $tournament)
    {
        $tournament->poster->delete();

        return $tournament->load('poster')->poster;
    }
}
