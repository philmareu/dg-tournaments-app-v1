<?php

namespace DGTournaments\Http\Controllers\User;

use DGTournaments\Http\Controllers\Controller;
use DGTournaments\Models\Classes;
use DGTournaments\Models\Format;
use DGTournaments\Models\SpecialEventType;
use DGTournaments\Models\StripeAccount;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Account;
use Stripe\Stripe;

class TournamentManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index()
    {
        if(auth()->guest()) return view('pages.manage.tools.guest');

        return view('pages.manage.tools.index')
            ->withTournaments(auth()->user()->managing->sortBy('start'));
    }

    public function submit()
    {
        return view('pages.manage.tools.submit')
            ->withFormats(Format::all())
            ->withClasses(Classes::all())
            ->withTimezones(timezone_identifiers_list())
            ->withSpecialEventTypes(SpecialEventType::all())
            ->withUser(Auth::user());
    }

    public function sponsors()
    {
        return view('pages.manage.tools.sponsors')
            ->withUser(Auth::user());
    }

    public function stripe()
    {
        $url = 'https://connect.stripe.com/oauth/authorize?'
            . http_build_query([
                'response_type' => 'code',
                'scope'         => 'read_write',
                'client_id'     => config('services.stripe.client_id')
            ]);

        return view('pages.manage.tools.stripe')
            ->withUrl($url)
            ->withUser(Auth::user());
    }

    public function connect(Request $request)
    {
        if($request->has('code'))
        {
            $client = new Client();

            $response = $client->post('https://connect.stripe.com/oauth/token', [
                'form_params' => [
                    'client_secret' => config('services.stripe.secret'),
                    'client_id' => config('services.stripe.client_id'),
                    'code' => $request->code,
                    'grant_type'    => 'authorization_code',
                ]
            ]);

            if($response->getStatusCode() == 200)
            {
                $data = json_decode($response->getBody()->getContents());

                Stripe::setApiKey($data->access_token);
                $account = Account::retrieve($data->stripe_user_id);

                $stripeAccount = new StripeAccount([
                    'access_token' => $data->access_token,
                    'display_name' => $account->display_name,
                    'stripe_user_id' => $data->stripe_user_id
                ]);

                Auth::user()->stripeAccounts()->save($stripeAccount);
            }

            return redirect('manage/stripe')->with('success', 'You are now connected with Stripe.');
        }

        if($request->has('error'))
        {
            return redirect('manage/stripe')->with('failed', 'Account not connected.');
        }
    }

    public function deactivate(StripeAccount $userStripeAccount)
    {
        $client = new Client();

        $response = $client->post('https://connect.stripe.com/oauth/deauthorize', [
            'headers' => [
                'Authorization' => "Bearer sk_test_dlr9wcCqSgKnKzmlnl6Z68vT"
            ],
            'body' => [
                'client_id' => config('services.stripe.client_id'),
                'stripe_user_id' => $userStripeAccount->stripe_user_id
            ]
        ]);

        if($response->getStatusCode() == 200 && $response->json()['stripe_user_id'] == $userStripeAccount->stripe_user_id)
        {
            $userStripeAccount->delete();

            return Auth::user()->load('stripeAccounts')->stripeAccounts;
        }

        return false;
    }

    public function getStripeAccounts()
    {
        return Auth::user()->stripeAccounts;
    }
}
