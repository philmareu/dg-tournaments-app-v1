<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\Link;
use DGTournaments\Models\TournamentLink;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LinkValidationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function storing_a_tournament_link_requires_a_title()
    {
        $this->storing('title');
    }

    /** @test */
    public function storing_a_tournament_link_requires_a_url()
    {
        $this->storing('url');
    }

    /** @test */
    public function storing_a_tournament_link_requires_a_valid_url()
    {
        $this->storing('url', ['url' => 'not a valid url']);
    }

    /** @test */
    public function updating_a_tournament_link_requires_a_title()
    {
        $this->updating('title');
    }

    /** @test */
    public function updating_a_tournament_link_requires_a_url()
    {
        $this->updating('url');
    }

    /** @test */
    public function updating_a_tournament_link_requires_a_valid_url()
    {
        $this->updating('url', ['url' => 'not a valid url']);
    }

    private function storing($key, $data = [])
    {

        list($user, $tournament) = $this->createTournamentWithManager();

        $response = $this->actingAs($user)
            ->json('POST', 'tournament/links/' . $tournament->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }

    private function updating($key, $data = [])
    {

        list($user, $tournament) = $this->createTournamentWithManager();
        $tournament->links()->save(factory(Link::class)->make());

        $response =$this->actingAs($user)
            ->json('PUT', 'tournament/links/' . $tournament->links->first()->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }
}
