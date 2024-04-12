<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Http\Requests\Manager\DestroyTournamentLinkRequest;
use DGTournaments\Http\Requests\Manager\StoreTournamentLinkRequest;
use DGTournaments\Http\Requests\Manager\UpdateTournamentLinkRequest;
use DGTournaments\Models\Link;
use DGTournaments\Models\Tournament;
use DGTournaments\Http\Controllers\Controller;

class LinksEndpointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StoreTournamentLinkRequest $request, Tournament $tournament)
    {
        $tournament->links()->create($request->only('title', 'url', 'ordinal'));

        return $tournament->links;
    }

    public function update(UpdateTournamentLinkRequest $request, Link $link)
    {
        $link->update($request->only('title', 'url', 'ordinal'));

        return $link->tournament->links;
    }

    public function destroy(DestroyTournamentLinkRequest $request, Link $link)
    {
        $link->delete();

        return $link->tournament->links;
    }
}
