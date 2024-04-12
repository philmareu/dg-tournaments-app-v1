<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use Carbon\Carbon;
use DGTournaments\Http\Requests\Manager\DestroyTournamentSponsorRequest;
use DGTournaments\Http\Requests\Manager\StoreTournamentSponsorRequest;
use DGTournaments\Http\Requests\Manager\UpdateTournamentSponsorRequest;
use DGTournaments\Models\Sponsor;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Http\Controllers\Controller;
use DGTournaments\Models\TournamentSponsor;

class TournamentSponsorsEndpointController extends Controller
{
    protected $sponsor;

    protected $sponsorship;

    public function __construct(Sponsor $sponsor, Sponsorship $sponsorship)
    {
        $this->sponsorship = $sponsorship;
        $this->sponsor = $sponsor;

        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(StoreTournamentSponsorRequest $request, Sponsorship $sponsorship)
    {
        $sponsor = $this->sponsor->find($request->sponsor_id);

        $tournamentSponsor = new TournamentSponsor;
        $tournamentSponsor->sponsorship()->associate($sponsorship);
        $tournamentSponsor->tournament()->associate($sponsorship->tournament);
        $tournamentSponsor->sponsor()->associate($sponsor);
        $tournamentSponsor->save();

        return $sponsorship->tournamentSponsors->load('sponsor.logo');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array
     */
    public function update(UpdateTournamentSponsorRequest $request, TournamentSponsor $tournamentSponsor)
    {
        $sponsor = $this->sponsor->find($request->sponsor_id);

        $tournamentSponsor->sponsor()->associate($sponsor)->save();

        return $tournamentSponsor->sponsorship->tournamentSponsors->load('sponsor.logo');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy(DestroyTournamentSponsorRequest $request, TournamentSponsor $tournamentSponsor)
    {
        $tournamentSponsor->delete();

        return $tournamentSponsor->sponsorship->tournamentSponsors->load('sponsor.logo');
    }
}
