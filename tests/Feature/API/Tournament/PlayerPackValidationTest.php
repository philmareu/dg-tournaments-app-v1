<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\PlayerPack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlayerPackValidationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function storing_a_player_pack_requires_a_title()
    {

        $playerPack = factory(PlayerPack::class)->make();
        $tournament = $this->createTournament();
        $playerPack->tournament()->associate($tournament);
        $playerPack->save();
        $user = $this->createUser();
        $user->managing()->save($tournament);
        $this->be($user);

        $response = $this->json('POST', '/tournament/player-packs/' . $tournament->id);
        $this->assertArrayHasKey('title', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_player_pack_requires_a_title()
    {

        $playerPack = factory(PlayerPack::class)->make();
        $tournament = $this->createTournament();
        $playerPack->tournament()->associate($tournament);
        $playerPack->save();
        $user = $this->createUser();
        $user->managing()->save($tournament);
        $this->be($user);

        $response = $this->json('PUT', '/tournament/player-packs/' . $playerPack->id);
        $this->assertArrayHasKey('title', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_player_pack_item_requires_a_title()
    {


        list($user, $tournament) = $this->createTournamentWithManager();
        $playerPack = $tournament->playerPacks()->save(factory(PlayerPack::class)->make());

        $response = $this->actingAs($user)
            ->json('POST', '/tournament/player-pack/items/' . $playerPack->id);

        $this->assertArrayHasKey('title', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_player_pack_item_requires_a_title()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $user->managing()->save($tournament);

        $this->actingAs($user)
            ->json('POST', '/tournament/player-packs/' . $tournament->id, [
                'title' => 'Test Title'
            ]);

        $playerPack = $tournament->fresh()->playerPacks->first();
        $playerPackItem = $playerPack->items()->create(['title' => 'test']);

        $response = $this->actingAs($user)
            ->json('PUT', '/tournament/player-pack/items/' . $playerPackItem->id);

        $this->assertArrayHasKey('title', $response->getOriginalContent()['errors']);
    }
}
