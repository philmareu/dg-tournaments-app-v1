<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Http\Requests\Manager\DestroyPlayerPackRequest;
use DGTournaments\Http\Requests\Manager\StorePlayerPackRequest;
use DGTournaments\Http\Requests\Manager\UpdatePlayerPackRequest;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\PlayerPack;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class PlayerPacksEndpointController extends Controller
{
    protected $playerPack;

    protected $tournament;

    public function __construct(PlayerPack $playerPack, Tournament $tournament)
    {
        $this->tournamentPlayerPack = $playerPack;
        $this->tournament = $tournament;

        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePlayerPackRequest $request, Tournament $tournament)
    {
        $playerPack = new PlayerPack($request->only('title', 'description'));
        $playerPack->tournament()->associate($tournament)->save();

        return $tournament->playerPacks;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlayerPackRequest $request, PlayerPack $playerPack)
    {
        $playerPack->update($request->only('title', 'description'));

        return $this->getPlayerPacks($playerPack);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyPlayerPackRequest $request, PlayerPack $playerPack)
    {
        $playerPack->delete();

        return $this->getPlayerPacks($playerPack);
    }

    /**
     * @param PlayerPack $playerPack
     * @return mixed
     */
    public function getPlayerPacks(PlayerPack $playerPack)
    {
        return $playerPack->tournament->playerPacks;
    }
}
