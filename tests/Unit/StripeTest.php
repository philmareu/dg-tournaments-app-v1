<?php

namespace Tests\Unit;

use DGTournaments\Billing\Stripe\StripeBilling;
use DGTournaments\Data\Price;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StripeTest extends TestCase
{
    /**
     * @test
     */
    public function guest_charge_is_made_with_stripe()
    {
        $response = $this->billingClass()->charge()->create(new Price(100), 1, 'tok_visa');

        $this->assertEquals('succeeded', $response->status);
    }

    /**
     * @test
     */
    public function a_customer_can_be_saved_in_stripe()
    {
        $response = $this->billingClass()->customer()->create('email@email.com', 'tok_visa');

        $this->assertEquals('email@email.com', $response->email);
    }

    /**
     * @test
     */
    public function a_charge_can_be_made_with_an_existing_customer_card()
    {
        $customer = $this->billingClass()->customer()->create('email@email.com', 'tok_visa');

        $response = $this->billingClass()->charge()->create(new Price(100), 1, $customer->sources->data[0]->id, $customer->id);

        $this->assertEquals('succeeded', $response->status);
    }

    private function billingClass()
    {
        return new StripeBilling;
    }
}
