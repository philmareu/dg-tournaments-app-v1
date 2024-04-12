<?php

namespace Tests\Feature\Endpoints\Order;

use DGTournaments\Models\Order;
use DGTournaments\Models\Order\OrderSponsorshipProduct;
use DGTournaments\Models\OrderSponsorship;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\StripeAccount;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PayOrderEndpointTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function order_returned_as_paid_when_charge_is_successful()
    {
        Event::fake();

        $tournament1 = $this->createTournament();
        $stripeAccount = factory(StripeAccount::class)->create([
            'access_token' => env('STRIPE_TEST_ACCOUNT_1_SECRET'),
            'stripe_user_id' => env('STRIPE_TEST_ACCOUNT_1_ACCOUNT')
        ]);
        $tournament1->stripeAccount()->associate($stripeAccount)->save();
        $sponsorship = factory(Sponsorship::class)->create([
            'tournament_id' => $tournament1->id
        ]);
        $orderSponsorship = factory(OrderSponsorship::class)->create([
            'sponsorship_id' => $sponsorship->id
        ]);

        $this->json('POST', 'order/checkout/pay', ['source' => ['source' => 'tok_visa'], 'unique' => $orderSponsorship->order->unique])
            ->assertJson([
                'paid' => 1
            ]);
    }
}
