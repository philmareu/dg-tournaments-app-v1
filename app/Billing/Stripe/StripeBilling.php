<?php

namespace DGTournaments\Billing\Stripe;

use Stripe\Stripe;

class StripeBilling
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function charge()
    {
        return new StripeCharge;
    }

    public function customer()
    {
        return new StripeCustomer;
    }

    public function transfer()
    {
        return new StripeTransfer;
    }
}