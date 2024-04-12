<?php

namespace DGTournaments\Repositories;


use DGTournaments\Events\TournamentClaimRequestSubmitted;
use DGTournaments\Models\ClaimRequest;

class ClaimRepository
{
    protected $claimRequest;

    public function __construct(ClaimRequest $claimRequest)
    {
        $this->claimRequest = $claimRequest;
    }

    public function createToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    public function tournamentAlreadyHasRequest($tournament)
    {
        return ! is_null($tournament->claimRequest);
    }

    public function userAlreadyManages($tournament, $user)
    {
        return in_array($user->id, $tournament->managers->pluck('id')->toArray());
    }

    public function createClaimRequest($tournament, $user)
    {
        $claim = new ClaimRequest(['token' => $this->createToken()]);
        $claim->user()->associate($user);
        $claim->tournament()->associate($tournament);
        $claim->save();

        event(new TournamentClaimRequestSubmitted($claim));
    }

    public function getClaimByToken($token)
    {
        return $this->claimRequest->whereToken($token)->first();
    }
}