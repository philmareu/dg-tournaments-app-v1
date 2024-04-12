<?php

namespace DGTournaments\Http\Controllers\User;

use DGTournaments\Http\Requests\User\StoreReferralRequest;
use DGTournaments\Mail\User\SendReferral;
use DGTournaments\Models\User\UserReferral;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ReferralsController extends Controller
{
    protected $referral;

    public function __construct(UserReferral $referral)
    {
        $this->referral = $referral;

        $this->middleware('auth');
    }

    public function create()
    {
        return view('pages.account.referral');
    }

    public function store(StoreReferralRequest $request)
    {
        $referral = new UserReferral($request->only('email'));
        $referral->code = hash_hmac('sha256', $request->email, config('app.key'));
        $referral->referredBy()->associate(auth()->user());
        $referral->save();

        // Email referral
        Mail::to($request->email)->send(new SendReferral($referral));

        return redirect()->route('referral.create')->with('success', 'Awesome! Your referral has been sent. Thanks!');
    }

    public function invite(Request $request)
    {
        $referral = $this->referral->where('code', $request->code)->first();

        if(is_null($referral)) abort('404');

        return view('auth.register')
            ->with('referral', $referral);
    }
}
