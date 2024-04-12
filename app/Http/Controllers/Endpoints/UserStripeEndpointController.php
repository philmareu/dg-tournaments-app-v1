<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Http\Requests\Endpoints\DestroyStripeAccountRequest;
use DGTournaments\Models\StripeAccount;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class UserStripeEndpointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function destroy(DestroyStripeAccountRequest $request, StripeAccount $stripeAccount)
    {
        $stripeAccount->delete();

        return $request->user()->load('stripeAccounts')->stripeAccounts;
    }
}
