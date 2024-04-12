<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Data\Price;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\Tournament;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SponsorshipTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function only_a_manager_can_store_a_sponsorship_product()
    {

        $this->actingAs($this->createUser())
            ->json('POST', 'tournament/sponsorships/' . factory(Tournament::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function only_a_manager_can_update_a_sponsorship_product()
    {

        $this->actingAs($this->createUser())
            ->json('PUT', 'tournament/sponsorships/' . factory(Sponsorship::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function only_a_manager_can_delete_a_sponsorship_product()
    {

        $this->actingAs($this->createUser())
            ->json('DELETE', 'tournament/sponsorships/' . factory(Sponsorship::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function manager_can_store_a_new_sponsorship_product()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $data = [
            'title' => 'Sponsorship Product Title',
            'tier' => 1,
            'quantity' => 10,
            'cost_in_dollars' => 100,
            'description' => 'This is the test description'
        ];

        $this->actingAs($user)
            ->json('POST', 'tournament/sponsorships/' . $tournament->id, $data);

        $sponsorship = $tournament->sponsorships->first();

        $this->assertEquals($data['title'], $sponsorship->title);
        $this->assertEquals($data['tier'], $sponsorship->tier);
        $this->assertEquals($data['quantity'], $sponsorship->quantity);
        $this->assertInstanceOf(Price::class, $sponsorship->cost);
        $this->assertEquals($data['description'], $sponsorship->description);
    }

    /** @test */
    public function manager_can_update_a_sponsorship_product()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $tournament->sponsorships()->save(factory(Sponsorship::class)->make());

        $data = [
            'title' => 'Sponsorship Product Title',
            'tier' => 1,
            'quantity' => 10,
            'cost_in_dollars' => 100,
            'description' => 'This is the test description'
        ];

        $this->actingAs($user)
            ->json('PUT', 'tournament/sponsorships/' . $tournament->sponsorships->first()->id, $data);

        $sponsorship = $tournament->fresh()->sponsorships->first();

        $this->assertEquals($data['title'], $sponsorship->title);
        $this->assertEquals($data['tier'], $sponsorship->tier);
        $this->assertEquals($data['quantity'], $sponsorship->quantity);
        $this->assertInstanceOf(Price::class, $sponsorship->cost);
        $this->assertEquals($data['description'], $sponsorship->description);
    }

    /** @test */
    public function manager_can_delete_a_sponsorship_product()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $tournament->sponsorships()->save(factory(Sponsorship::class)->make());

        $data = [
            'title' => 'SponsorshipProduct Title',
            'url' => 'http://testing.com',
            'ordinal' => 10
        ];

        $this->actingAs($user)
            ->json('DELETE', 'tournament/sponsorships/' . $tournament->sponsorships->first()->id, $data);

        $this->assertEquals(0, $tournament->load('sponsorships')->sponsorships->count());
    }
}
