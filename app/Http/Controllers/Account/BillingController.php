<?php

namespace DGTournaments\Http\Controllers\Account;

use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class BillingController extends Controller
{
    public function index()
    {
        return view('pages.account.billing');
    }

    public function billing()
    {
        $user = $this->user;
        $cu = $this->billing->getCustomer($user->stripe_id);

        return view('auth.account.billing', compact('cu'));
    }

    public function updateCards(UpdateCardsRequest $request)
    {
        $default = $request->get('default');
        $deleted = $request->get('delete');

        if(isset($deleted))
        {
            foreach($deleted as $cardId)
            {
                $this->billing->deleteCard($this->user->stripe_id, $cardId);

                if($default == $cardId) $default = null;
            }
        }

        $cu = $this->billing->getCustomer($this->user->stripe_id);

        if( ! is_null($default))
        {
            $cu->default_card = $default;
            $cu->save();
        }

        return redirect('account/billing')->with('success', 'Cards updated');
    }

    public function addCard()
    {
        return view('auth.account.add_card');
    }

    public function saveCard(AddCCRequest $request)
    {
        if($this->user->stripe_id)
        {
            $this->billing->addCard($this->user->stripe_id, $request->get('stripe-token'));
        }
        else
        {
            $customer = $this->billing->createCustomer([
                'description' => 'Customer for ' . $this->user->email,
                'source' => $request->get('stripe-token'),
                'email' => $this->user->email
            ]);

            $this->user->stripe_id = $customer->id;
            $this->user->save();
        }

        return redirect('account/billing')->with('success', 'Card Added');
    }
}
