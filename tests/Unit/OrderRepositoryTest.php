<?php

namespace Tests\Unit;

use DGTournaments\Billing\Stripe\StripeBilling;
use DGTournaments\Models\Order;
use DGTournaments\Models\Order\OrderSponsorshipProduct;
use DGTournaments\Models\OrderSponsorship;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\TournamentOrder;
use DGTournaments\Models\TournamentOrderSponsorshipProduct;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function should_return_a_new_order_when_one_does_not_exist()
    {
        $order = $this->getRepo()->getOrderOrCreateNew();
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    /**
     * @test
     */
    public function new_order_is_created_if_unique_is_not_found_in_database()
    {
        $order = $this->getRepo()->getOrderOrCreateNew('thisDoesNotExistInDatabase');

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    /**
     * @test
     */
    public function should_save_a_sponsorship_product_to_a_tournament_order()
    {
        $sponsorship = factory(Sponsorship::class)->create();

        $order = $this->getRepo()->addSponsorship($sponsorship);

        $this->assertDatabaseHas('order_sponsorships', [
            'order_id' => $order->id,
            'sponsorship_id' => $sponsorship->id
        ]);
    }

    /**
     * @test
     */
    public function should_return_paid_when_submitting_valid_credentials()
    {
        $orderSponsorship = factory(OrderSponsorship::class)->create();

        Event::fake();

        $order = $this->getRepo()->pay($orderSponsorship->order, ['source' => 'tok_visa']);

        $this->assertEquals(1, $order->paid);
    }

    private function getRepo()
    {
        return new \DGTournaments\Repositories\OrderRepository(
            new Order(),
            new StripeBilling()
        );
    }
}
