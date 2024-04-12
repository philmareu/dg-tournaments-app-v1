<?php

namespace Tests\Feature\Endpoints\Order;

use DGTournaments\Models\Order;
use DGTournaments\Models\Order\OrderSponsorshipProduct;
use DGTournaments\Models\OrderSponsorship;
use DGTournaments\Models\Sponsorship;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SponsorshipProductsEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /**
     * @test
     */
    public function order_cookie_is_returned_with_adding_sponsorship_to_order()
    {
        $sponsorship = factory(Sponsorship::class)->create();

        $this->json('PUT', 'order/sponsorships/' . $sponsorship->id)
            ->assertCookie('_oo');
    }

    /**
     * @test
     */
    public function sponsorship_product_can_be_added_to_order()
    {
        $sponsorship = factory(Sponsorship::class)->create();

        $response = $this->json('PUT', 'order/sponsorships/' . $sponsorship->id);

        $this->assertDatabaseHas('order_sponsorships', [
            'order_id' => $response->getOriginalContent()->id,
            'sponsorship_id' => $sponsorship->id,
            'cost' => $sponsorship->cost->inCents()
        ]);
    }

    /**
     * @test
     */
    public function sponsorship_product_can_be_deleted_from_order()
    {
        $orderSponsorship = factory(OrderSponsorship::class)->create();

        $this->json('DELETE', 'order/sponsorships/' . $orderSponsorship->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('order_sponsorships', [
            'id' => $orderSponsorship->id
        ]);
    }

    /**
     * @test
     */
    public function sponsorships_can_not_be_deleted_from_paid_orders()
    {
        $orderSponsorship = factory(OrderSponsorship::class)->create();
        $order = $orderSponsorship->order;
        $order->paid = 1;
        $order->save();

        $this->json('DELETE', 'order/sponsorships/' . $orderSponsorship->id)
            ->assertStatus(200);

        // Assert that it does still exist

        $this->assertDatabaseHas('order_sponsorships', [
            'id' => $orderSponsorship->id,
            'order_id' => $order->id
        ]);
    }

    /**
     * @test
     */
    public function data_is_loaded_when_adding_sponsorships()
    {
        $sponsorship = factory(Sponsorship::class)->create();

        $this->json('PUT', 'order/sponsorships/' . $sponsorship->id)
            ->assertJson([
                'sponsorships' => [
                    [
                        'id' => $sponsorship->id,
                        'sponsorship' => [
                            'tournament' => [
                                'poster' => []
                            ]
                        ],
                        'cost' => $sponsorship->cost->inCents()
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function data_is_loaded_when_removing_sponsorships()
    {
        $order = factory(Order::class)->create();

        factory(OrderSponsorship::class, 2)->create([
            'order_id' => $order->id
        ]);

        $this->json('DELETE', 'order/sponsorships/1')
            ->assertJson([
                'sponsorships' => [
                    [
                        'id' => 2,
                        'sponsorship' => [
                            'tournament' => [
                                'poster' => []
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
