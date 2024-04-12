<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\PlayerPack;
use DGTournaments\Models\PlayerPackItem;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\TournamentFormat;
use DGTournaments\Models\TournamentPlayerPack;
use DGTournaments\Models\TournamentPlayerPackItem;
use DGTournaments\Models\User\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlayerPackTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function guest_cannot_store_player_pack()
    {
        $tournament = $this->createTournament();

        $this->assertFalse(Auth::check());

        $this->json('POST', 'tournament/player-packs/' . $tournament->id)
            ->assertStatus(401);
    }

    /** @test */
    public function user_must_have_access_to_tournament_to_store_player_pack()
    {
        $this->actingAs($this->createUser())
            ->json('POST', 'tournament/player-packs/' . factory(Tournament::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function user_must_have_access_to_tournament_to_update_player_pack()
    {
        $playerPack = factory(PlayerPack::class)->make();
        $tournament = $this->createTournament();
        $playerPack->tournament()->associate($tournament)->save();
        $user = $this->createUser();

        $this->actingAs($this->createUser())
            ->json('PUT', '/tournament/player-packs/' . $playerPack->id)
            ->assertStatus(403);
    }

    /** @test */
    public function user_must_have_access_to_tournament_to_delete_player_pack()
    {
        $playerPack = factory(PlayerPack::class)->make();
        $tournament = $this->createTournament();
        $playerPack->tournament()->associate($tournament)->save();
        $user = $this->createUser();

        $this->actingAs($this->createUser())
            ->json('DELETE', '/tournament/player-packs/' . $playerPack->id)
            ->assertStatus(403);
    }

    /** @test */
    public function manager_can_store_player_pack()
    {
        $tournament = $this->createTournament();
        $user = $this->createUser();
        $user->managing()->save($tournament);

        $response = $this->actingAs($user)
            ->json('POST', 'tournament/player-packs/' . $tournament->id, [
                'title' => 'test title',
                'description' => 'test description'
            ])
            ->assertStatus(200)
            ->assertJson([
                [
                    'title' => 'test title',
                    'description' => 'test description'
                ]
            ]);

        $tournament->fresh();
        $this->assertEquals('test title', $tournament->playerPacks->first()->title);
        $this->assertEquals('test description', $tournament->playerPacks->first()->description);
    }

    /** @test */
    public function manager_can_update_player_pack()
    {
        $playerPack = factory(PlayerPack::class)->create();
        $tournament = $playerPack->tournament;
        $user = $this->createUser();
        $user->managing()->save($tournament);

        $this->actingAs($user)
            ->json('PUT', 'tournament/player-packs/' . $playerPack->id, [
                'title' => 'updated title',
                'description' => 'updated description'
            ])
            ->assertStatus(200)
            ->assertJson([
                [
                    'title' => 'updated title',
                    'description' => 'updated description'
                ]
            ]);

        $tournament->fresh();
        $this->assertEquals('updated title', $tournament->playerPacks->first()->title);
        $this->assertEquals('updated description', $tournament->playerPacks->first()->description);
    }

    /** @test */
    public function manager_can_delete_player_pack()
    {
        $playerPack = factory(PlayerPack::class)->make();
        $tournament = $this->createTournament();
        $playerPack->tournament()->associate($tournament);
        $playerPack->save();
        $user = $this->createUser();
        $user->managing()->save($tournament);

        $response = $this->actingAs($user)
            ->json('DELETE', '/tournament/player-packs/' . $playerPack->id)
            ->assertStatus(200)
            ->assertJson([]);

        $tournament->fresh();
        $this->assertNull(PlayerPack::find($playerPack->id));
    }



    /** @test */
    public function storing_a_player_pack_does_not_require_a_description()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $user->managing()->save($tournament);

        $response = $this->actingAs($user)
            ->json('POST', '/tournament/player-packs/' . $tournament->id, [
                'title' => 'Test Title'
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function only_a_manager_can_add_items_to_player_packs()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $user->managing()->save($tournament);

        $response = $this->actingAs($user)
            ->json('POST', '/tournament/player-packs/' . $tournament->id, [
                'title' => 'Test Title'
            ]);

        $playerPack = $tournament->fresh()->playerPacks->first();

        $this->actingAs($this->createUser())
            ->json('POST', '/tournament/player-pack/items/' . $playerPack->id, [
                'title' => 'PP Item'
            ])
            ->assertStatus(403);
    }

    /** @test */
    public function only_a_manager_can_update_items_to_player_packs()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $user->managing()->save($tournament);

        $response = $this->actingAs($user)
            ->json('POST', '/tournament/player-packs/' . $tournament->id, [
                'title' => 'Test Title'
            ]);

        $playerPack = $tournament->fresh()->playerPacks->first();
        $playerPackItem = $playerPack->items()->create(['title' => 'test']);

        $this->actingAs($this->createUser())
            ->json('PUT', '/tournament/player-pack/items/' . $playerPackItem->id, [
                'title' => 'PP Item'
            ])
            ->assertStatus(403);
    }

    /** @test */
    public function only_a_manager_can_delete_items_to_player_packs()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $user->managing()->save($tournament);

        $response = $this->actingAs($user)
            ->json('POST', '/tournament/player-packs/' . $tournament->id, [
                'title' => 'Test Title'
            ]);

        $playerPack = $tournament->fresh()->playerPacks->first();
        $playerPackItem = $playerPack->items()->create(['title' => 'test']);

        $this->actingAs($this->createUser())
            ->json('DELETE', '/tournament/player-pack/items/' . $playerPackItem->id)
            ->assertStatus(403);
    }

    /** @test */
    public function a_manager_can_add_an_item_to_a_player_pack()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $user->managing()->save($tournament);
        $this->be($user);

        $this->json('POST', '/tournament/player-packs/' . $tournament->id, [
                'title' => 'Test Title'
            ]);

        $playerPack = $tournament->fresh()->playerPacks->first();

        $this->json('POST', '/tournament/player-pack/items/' . $playerPack->id, [
                'title' => 'PP Item'
            ])
            ->assertStatus(200)
            ->assertJson([
                [
                    'id' => 1,
                    'title' => 'PP Item'
                ]
            ]);

        $this->assertEquals('PP Item', $tournament->fresh()->playerPacks->first()->items->first()['title']);
    }

    /** @test */
    public function a_manager_can_update_an_item_to_a_player_pack()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $user->managing()->save($tournament);
        $playerPackItem = $tournament->playerPacks()->create(['title' => 'Test'])->items()->create(['title' => 'Test Item']);
        $this->be($user);

        $response = $this->json('PUT', '/tournament/player-pack/items/' . $playerPackItem->id, [
            'title' => 'PP Item'
        ])
            ->assertStatus(200)
            ->assertJson([
                [
                    'id' => 1,
                    'title' => 'PP Item'
                ]
            ]);

        $this->assertEquals('PP Item', $tournament->fresh()->playerPacks->first()->items->first()['title']);
    }

    /** @test */
    public function a_manager_can_destroy_an_item_to_a_player_pack()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $user->managing()->save($tournament);
        $playerPackItem = $tournament->playerPacks()->create(['title' => 'Test'])->items()->create(['title' => 'Test Item']);
        $this->be($user);

        $response = $this->json('DELETE', '/tournament/player-pack/items/' . $playerPackItem->id)->assertStatus(200)
            ->assertJson([]);
        $this->assertNull(PlayerPackItem::find($playerPackItem->id));
    }
}
