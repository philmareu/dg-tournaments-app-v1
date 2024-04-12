<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\Sponsor;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\TournamentSponsor;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SponsorEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function only_a_manager_can_store_a_tournament_sponsor()
    {

        $this->actingAs($this->createUser())
            ->json('POST', 'tournament/sponsorship/sponsors/' . factory(Sponsorship::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function only_a_manager_can_update_a_tournament_sponsor()
    {

        $this->actingAs($this->createUser())
            ->json('PUT', 'tournament/sponsorship/sponsors/' . factory(TournamentSponsor::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function only_a_manager_can_delete_a_tournament_sponsor()
    {

        $this->actingAs($this->createUser())
            ->json('DELETE', 'tournament/sponsorship/sponsors/' . factory(TournamentSponsor::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function manager_can_store_a_new_tournament_sponsor()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $sponsor = factory(Sponsor::class)->create();
        $sponsorship = factory(Sponsorship::class)->create();
        $product = $tournament->sponsorships()->save($sponsorship);

        $data = [
            'sponsor_id' => $sponsor->id
        ];

        $this->actingAs($user)
            ->json('POST', 'tournament/sponsorship/sponsors/' . $sponsorship->id, $data)
            ->assertJson([
                [
                    'sponsor_id' => $sponsor->id,
                    'sponsorship_id' => $sponsorship->id,
                    'tournament_id' => $tournament->id
                ]
            ]);

        $this->assertDatabaseHas('tournament_sponsors', [
            'sponsor_id' => $sponsor->id,
            'sponsorship_id' => $sponsorship->id,
            'tournament_id' => $tournament->id
        ]);
    }

    /** @test */
    public function manager_has_ability_to_update_a_tournament_sponsor()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $tournamentSponsor = factory(TournamentSponsor::class)->create();
        $tournament->sponsors()->save($tournamentSponsor);

        $newSponsor = factory(Sponsor::class)->create();

        $data = [
            'sponsor_id' => $newSponsor->id
        ];

        $this->actingAs($user)
            ->json('PUT', 'tournament/sponsorship/sponsors/' . $tournamentSponsor->id, $data)
            ->assertJson([
                [
                    'sponsor_id' => $newSponsor->id,
                    'sponsorship_id' => $tournamentSponsor->sponsorship_id,
                    'tournament_id' => $tournament->id
                ]
            ]);
    }

    /** @test */
    public function manager_can_delete_a_tournament_sponsor()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $tournamentSponsor = factory(TournamentSponsor::class)->create();

        $this->actingAs($user)
            ->json('DELETE', 'tournament/sponsorship/sponsors/' . $tournamentSponsor->id)
            ->assertJson([]);

        $this->assertEquals(0, $tournament->load('sponsors')->sponsors->count());
    }
}
