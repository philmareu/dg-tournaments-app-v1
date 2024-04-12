<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Http\Requests\Manager\DestroyPlayerPackItemRequest;
use DGTournaments\Http\Requests\Manager\StorePlayerPackItemRequest;
use DGTournaments\Http\Requests\Manager\UpdatePlayerPackItemRequest;
use DGTournaments\Models\PlayerPack;
use DGTournaments\Models\PlayerPackItem;
use Illuminate\Database\Eloquent\Model;
use DGTournaments\Http\Controllers\Controller;

class PlayerPackItemsEndpointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Model
     */
    public function store(StorePlayerPackItemRequest $request, PlayerPack $playerPack)
    {
        $playerPack->items()->create(['title' => $request->title]);

        return $playerPack->load('items')->items;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlayerPackItemRequest $request, PlayerPackItem $playerPackItem)
    {
        $playerPackItem->update($request->only('title'));

        return $playerPackItem->playerPack->load('items')->items;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyPlayerPackItemRequest $request, PlayerPackItem $playerPackItem)
    {
        $playerPackItem->delete();

        return $playerPackItem->playerPack->load('items')->items;
    }
}
