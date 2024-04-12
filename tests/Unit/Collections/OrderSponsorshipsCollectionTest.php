<?php

namespace Tests\Unit\Collections;

use DGTournaments\Models\Order;
use DGTournaments\Models\OrderSponsorship;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\Tournament;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderSponsorshipsCollectionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function should_return_a_collection_of_order_sponsorships_grouped_by_tournament_id()
    {
        $order = factory(Order::class)->create();

        // Tournament #1
        $tournament1 = factory(Tournament::class)->create();
        $tournament1Sponsorships = $tournament1->sponsorships()->saveMany(factory(Sponsorship::class, 3)->make());
        $tournament1Sponsorships->each(function (Sponsorship $sponsorship) use ($order) {
            $this->addSponsorshipToOrder($order, $sponsorship);
        });

        // Tournament #2
        $tournament2 = factory(Tournament::class)->create();
        $tournament2Sponsorships = $tournament2->sponsorships()->saveMany(factory(Sponsorship::class, 2)->make());
        $tournament2Sponsorships->each(function (Sponsorship $sponsorship) use ($order) {
            $this->addSponsorshipToOrder($order, $sponsorship);
        });

        $this->assertEquals([$tournament1->id, $tournament2->id], $order->sponsorships->sortSponsorshipsByTournament()->keys()->toArray());
    }

    private function addSponsorshipToOrder(Order $order, Sponsorship $sponsorship)
    {
        $orderSponsorship = new OrderSponsorship(['cost' => $sponsorship->cost->inDollars()]);
        $orderSponsorship->sponsorship()->associate($sponsorship);
        $orderSponsorship->order()->associate($order);

        $orderSponsorship->save();
    }
}
