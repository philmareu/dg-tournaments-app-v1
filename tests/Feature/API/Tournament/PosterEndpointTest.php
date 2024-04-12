<?php

namespace Tests\Feature\Endpoints\Tournament;

use DGTournaments\Models\Tournament;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PosterEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function guests_cannot_delete_tournament_poster()
    {

        $this->json('DELETE', 'tournament/poster/' . factory(Tournament::class)->create()->id)
            ->assertStatus(401);
    }

    /** @test */
    public function only_a_manager_can_delete_tournament_poster()
    {

        $this->actingAs($this->createUser())
            ->json('DELETE', 'tournament/poster/' . factory(Tournament::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function manager_can_delete_tournament_poster()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $this->actingAs($user)
            ->json('DELETE', 'tournament/poster/' . $tournament->id)
            ->assertJson([
                'id' => null
            ]);

        $this->assertNull($tournament->poster->id);
    }
}
