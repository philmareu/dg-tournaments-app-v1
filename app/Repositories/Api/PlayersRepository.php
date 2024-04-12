<?php namespace DGTournaments\Repositories\Api;

use DGTournaments\Models\Activity;
use DGTournaments\Models\Player;
use DGTournaments\Services\Pdga\PdgaApi;

class PlayersRepository
{
    protected $player;

    protected $pdgaApi;

    public function __construct(Player $player, PdgaApi $pdgaApi)
    {
        $this->player = $player;
        $this->pdgaApi = $pdgaApi;
    }

    public function getById($pdgaNumber)
    {
        return $this->player->find($pdgaNumber);
    }

    public function getFromPdga($pdgaNumber)
    {
        return $this->pdgaApi->getPlayerByPdgaNumber($pdgaNumber);
    }

    public function create($attributes)
    {
        $player = $this->player->forceCreate($attributes);
        $player->activities()->save(new Activity([
            'type' => 'player.created',
            'data' => $player->toJson()
        ]));

        return $player;
    }
}