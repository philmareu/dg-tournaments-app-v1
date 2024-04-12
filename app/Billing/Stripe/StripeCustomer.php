<?php

namespace DGTournaments\Billing\Stripe;


use Stripe\Customer;

class StripeCustomer
{
    public function create($email, $token = null, $arg = [])
    {
        return Customer::create(array_merge(
            [
                'source' => $token,
                'email' => $email
            ],
            $arg
        ));
    }

    public function retrieve($customer)
    {
        return Customer::retrieve($customer);
    }
}