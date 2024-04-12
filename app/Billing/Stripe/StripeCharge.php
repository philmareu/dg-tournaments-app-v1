<?php

namespace DGTournaments\Billing\Stripe;

use DGTournaments\Data\Price;
use Illuminate\Support\Facades\Log;
use Stripe\Charge;

class StripeCharge
{
    public function create(Price $price, $transferGroup, $token, $customerId = null)
    {
        return Charge::create([
            'amount' => $price->inCents(),
            'currency' => 'usd',
            'transfer_group' => $transferGroup,
            'source' => $token,
            'customer' => $customerId
        ]);
    }
}