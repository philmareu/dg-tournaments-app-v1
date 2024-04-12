<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Http\Requests\Endpoints\Tournament\StoreSponsorRequest;
use DGTournaments\Http\Requests\Endpoints\Tournament\UpdateSponsorRequest;
use DGTournaments\Http\Requests\Manager\DestroySponsorRequest;
use DGTournaments\Models\Sponsor;
use DGTournaments\Models\Upload;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserSponsorsEndpointController extends Controller
{
    protected $sponsor;

    protected $upload;

    public function __construct(Sponsor $sponsor, Upload $upload)
    {
        $this->sponsor = $sponsor;
        $this->upload = $upload;

        $this->middleware('auth');
    }

    public function list(Request $request)
    {
        return $request->user()->sponsors->load('logo');
    }

    public function store(StoreSponsorRequest $request)
    {
        $upload = $this->upload->find($request->upload_id);

        $sponsor = $this->sponsor->make($request->only('title', 'url'));
        $sponsor->logo()->associate($upload);
        $sponsor->user()->associate(Auth::user());
        $sponsor->save();

        return $request->user()->sponsors->load('logo');
    }

    public function update(UpdateSponsorRequest $request, Sponsor $sponsor)
    {
        if($request->has('upload_id'))
        {
            $upload = $this->upload->find($request->upload_id);
            $sponsor->logo()->associate($upload);
        }

        $sponsor->update($request->only('title', 'url'));

        return $request->user()->sponsors->load('logo');
    }

    public function destroy(DestroySponsorRequest $request, Sponsor $sponsor)
    {
        $sponsor->delete();

        return auth()->user()->sponsors->load('logo');
    }
}
