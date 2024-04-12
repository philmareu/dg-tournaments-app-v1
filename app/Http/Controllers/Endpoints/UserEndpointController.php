<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Billing\Stripe\StripeBilling;
use DGTournaments\Http\Requests\CreateStripeCustomerRequest;
use DGTournaments\Http\Requests\User\AddCardRequest;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserEndpointController extends Controller
{
    protected $billing;

    public function __construct(StripeBilling $billing)
    {
        $this->billing = $billing;
    }

    public function getCards()
    {
        if(is_null(Auth::user()->stripe_customer_id)) return [];

        return $this->billing->customer()
            ->retrieve(Auth::user()->stripe_customer_id)
            ->sources->all(['object' => 'card']);
    }

    public function addCard(AddCardRequest $request)
    {
        return $this->billing->customer()->retrieve(Auth::user()->stripe_customer_id)->sources->create([
            'source' => $request->token
        ]);
    }

    public function createCustomer(CreateStripeCustomerRequest $request)
    {
        $customer = $this->billing->customer()->create(Auth::user()->email, $request->token);

        Auth::user()->stripe_customer_id = $customer->id;
        Auth::user()->save();

        return $customer;
    }

    public function removeCard($cardId)
    {
        $customer = $this->billing->customer()->retrieve(Auth::user()->stripe_customer_id);

        $customer->sources->retrieve($cardId)->delete();

        return $customer;
    }
}
