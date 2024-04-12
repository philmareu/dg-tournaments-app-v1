<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use Carbon\Carbon;
use DGTournaments\Events\TournamentRegistrationUpdated;
use DGTournaments\Http\Requests\Endpoints\Tournament\StoreTournamentRegistrationRequest;
use DGTournaments\Http\Requests\Endpoints\Tournament\UpdateTournamentRegistrationRequest;
use DGTournaments\Models\Registration;
use DGTournaments\Models\Tournament;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class RegistrationEndpointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StoreTournamentRegistrationRequest $request, Tournament $tournament)
    {
        $tournament->registration()->create([
            'opens_at' => Carbon::createFromFormat('n-j-Y', $request->opens_at),
            'closes_at' => $request->filled('closes_at') ? Carbon::createFromFormat('n-j-Y', $request->closes_at) : null,
            'url' => $request->filled('url') ? $request->url : null
        ]);

        return $tournament->registration;
    }

    public function update(UpdateTournamentRegistrationRequest $request, Registration $registration)
    {
        if($request->has('opens_at')) $registration->opens_at = Carbon::createFromFormat('n-j-Y', $request->opens_at);
        if($request->filled('closes_at')) $registration->closes_at = Carbon::createFromFormat('n-j-Y', $request->closes_at);
        else $registration->closes_at = null;

        $registration->url = $request->has('url') ? $request->url : null;

        $registration->save();

        return $registration;
    }
}
