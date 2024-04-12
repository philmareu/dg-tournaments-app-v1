<?php

namespace DGTournaments\Billing\Stripe;


use DGTournaments\Data\Price;
use Stripe\Transfer;

class StripeTransfer
{
    public function create(Price $amount, $account, $chargeId)
    {
        return Transfer::create([
            'amount' => $amount->inCents(),
            'currency' => 'usd',
            'source_transaction' => $chargeId,
            'destination' => $account
        ]);
    }
}