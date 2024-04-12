<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Events\TournamentClaimApproved;
use DGTournaments\Models\Tournament;
use DGTournaments\Repositories\ClaimRepository;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserClaimEndpointController extends Controller
{
    protected $claimRepository;

    public function __construct(ClaimRepository $claimRepository)
    {
        $this->claimRepository = $claimRepository;
    }

    public function claim(Tournament $tournament)
    {
        if($tournament->managers()->count() > 0)
            return response()->json(['error' => 'Already claimed'], 403);

        if($this->claimRepository->userAlreadyManages($tournament, Auth::user()))
            return response()->json(['error' => 'Already manages'], 403);

        if($this->claimRepository->tournamentAlreadyHasRequest($tournament))
            return response()->json(['error' => 'Claim request already exists'], 403);

        $this->claimRepository->createClaimRequest($tournament, Auth::user());

        return $tournament->load('claimRequest');
    }
}
