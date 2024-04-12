<?php

namespace DGTournaments\Http\Controllers\Account;

use DGTournaments\Http\Requests\UpdateMembershipRequest;
use DGTournaments\Services\Pdga\PdgaApi;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class MembershipsController extends Controller
{
    protected $pdgaApi;

    public function __construct(PdgaApi $pdgaApi)
    {
        $this->pdgaApi = $pdgaApi;

        $this->middleware('auth');
    }

    public function edit()
    {
        return view('pages.account.memberships.edit')
            ->withUser(auth()->user());
    }

    public function update(UpdateMembershipRequest $request)
    {
        if($request->filled('pdga_number'))
        {
            if($request->pdga_number != $request->user()->pdga_number)
            {
                $pdgaData = $this->pdgaApi->getPlayerByPdgaNumber($request->pdga_number);

                if(is_null($pdgaData)) return redirect()->route('account.memberships')->withInput()->with('failed', 'Invalid PDGA Number');

                $request->user()->update([
                    'pdga_rating' => $pdgaData['rating'],
                    'pdga_number' => $request->pdga_number
                ]);
            }
        }
        else
        {
            $request->user()->update([
                'pdga_number' => null,
                'pdga_rating' => null
            ]);
        }

        return redirect()->route('account.memberships')->with('success', 'Memberships Updated');
    }
}
