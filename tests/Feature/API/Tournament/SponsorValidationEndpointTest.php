<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\Sponsor;
use DGTournaments\Models\TournamentSponsor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SponsorValidationEndpointTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function storing_a_tournament_sponsor_requires_a_sponsor_id()
    {
        $this->storing('sponsor_id');
    }

    /** @test */
    public function storing_a_tournament_sponsor_requires_an_existing_sponsor_id()
    {
        $this->storing('sponsor_id', ['sponsor_id' => 10]);
    }

    /** @test */
    public function updating_a_tournament_sponsor_id_requires_an_existing_sponsor_id()
    {
        $this->updating('sponsor_id', ['sponsor_id' => 10]);
    }

    private function storing($key, $data = [])
    {

        list($user, $tournament) = $this->createTournamentWithManager();

        $sponsorship = factory(Sponsorship::class)->create();
        $tournament->sponsorships()->save($sponsorship);

        $response = $this->actingAs($user)
            ->json('POST', 'tournament/sponsorship/sponsors/' . $sponsorship->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }

    private function updating($key, $data = [])
    {

        list($user, $tournament) = $this->createTournamentWithManager();

        $tournamentSponsor = factory(TournamentSponsor::class)->create();
        $tournament->sponsors()->save($tournamentSponsor);

        $response =$this->actingAs($user)
            ->json('PUT', 'tournament/sponsorship/sponsors/' . $tournamentSponsor->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }
}
