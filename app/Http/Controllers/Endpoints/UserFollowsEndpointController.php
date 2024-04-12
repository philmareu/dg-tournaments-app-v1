<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Events\TournamentFollowed;
use DGTournaments\Events\TournamentUnfollowed;
use DGTournaments\Http\Requests\User\FollowTournamentRequest;
use DGTournaments\Http\Controllers\Controller;
use DGTournaments\Models\Follow;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class UserFollowsEndpointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function tournament(Tournament $tournament)
    {
        if($this->tournamentIsFollowed($tournament)) $this->removeTournament($tournament);
        else $this->saveNewFollow($tournament);

        return $this->getCurrentUser()->load('following');
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|User
     */
    public function getCurrentUser()
    {
        return Auth::user();
    }

    private function tournamentIsFollowed(Tournament $tournament)
    {
        return (bool) $this->getCurrentUser()
            ->following()
            ->where('resource_type', 'DGTournaments\Models\Tournament')
            ->where('resource_id', $tournament->id)
            ->get()
            ->count();
    }

    private function removeTournament($tournament)
    {
        $this->getCurrentUser()
            ->following()
            ->where('resource_type', 'DGTournaments\Models\Tournament')
            ->where('resource_id', $tournament->id)
            ->get()
            ->each(function(Follow $follow) {
                $follow->delete();
            });
    }

    /**
     * @param Tournament $tournament
     */
    public function saveNewFollow($tournament)
    {
        $follow = new Follow();
        $follow->user()->associate($this->getCurrentUser());
        $follow->resource()->associate($tournament)->save();
        $this->getCurrentUser()->following()->save($follow);
    }
}
