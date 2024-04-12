<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\Sponsorship;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SponsorshipValidationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function storing_a_sponsorship_product_requires_a_title()
    {
        $this->storing('title');
    }

    /** @test */
    public function storing_a_sponsorship_product_requires_a_tier()
    {
        $this->storing('tier');
    }

    /** @test */
    public function storing_a_sponsorship_product_requires_a_tier_to_be_an_integer()
    {
        $this->storing('tier', ['tier' => 'Not an integer']);
    }

    /** @test */
    public function storing_a_sponsorship_product_requires_a_tier_to_be_less_than_100()
    {
        $this->storing('tier', ['tier' => 200]);
    }

    /** @test */
    public function storing_a_sponsorship_product_requires_a_quantity()
    {
        $this->storing('quantity');
    }

    /** @test */
    public function storing_a_sponsorship_product_requires_a_quantity_to_be_an_integer()
    {
        $this->storing('quantity', ['quantity' => 'Not an integer']);
    }

    /** @test */
    public function storing_a_sponsorship_product_requires_a_cost_in_dollars()
    {
        $this->storing('cost_in_dollars');
    }

    /** @test */
    public function storing_a_sponsorship_product_requires_a_cost_in_dollars_to_be_numeric()
    {
        $this->storing('cost_in_dollars', ['cost_in_dollars' => 'Not numeric']);
    }

    /** @test */
    public function updating_a_sponsorship_product_requires_a_tier_to_be_an_integer()
    {
        $this->updating('tier', ['tier' => 'Not an integer']);
    }

    /** @test */
    public function updating_a_sponsorship_product_requires_a_tier_to_be_less_than_100()
    {
        $this->updating('tier', ['tier' => 200]);
    }

    /** @test */
    public function updating_a_sponsorship_product_requires_a_quantity_to_be_an_integer()
    {
        $this->updating('quantity', ['quantity' => 'Not an integer']);
    }

    /** @test */
    public function updating_a_sponsorship_product_requires_a_cost_in_dollars_to_be_numeric()
    {
        $this->updating('cost_in_dollars', ['cost_in_dollars' => 'Not numeric']);
    }

    private function storing($key, $data = [])
    {

        list($user, $tournament) = $this->createTournamentWithManager();

        $response = $this->actingAs($user)
            ->json('POST', 'tournament/sponsorships/' . $tournament->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }

    private function updating($key, $data = [])
    {

        list($user, $tournament) = $this->createTournamentWithManager();
        $tournament->sponsorships()->save(factory(Sponsorship::class)->make());

        $response =$this->actingAs($user)
            ->json('PUT', 'tournament/sponsorships/' . $tournament->sponsorships->first()->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }
}
