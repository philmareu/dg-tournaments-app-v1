<?php

namespace DGTournaments\Http\Controllers\Account;

use DGTournaments\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('pages.account.settings');
    }

    public function update(UpdatePasswordRequest $request)
    {
        $request->user()->password = bcrypt($request->password);
        $request->user()->email = $request->email;
        $request->user()->save();

        return redirect()->route('account.settings')->with('success', 'Settings updated!');
    }
}
