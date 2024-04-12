<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Models\Activity;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class UserFeedController extends Controller
{
    public function index()
    {
        if(auth()->guest()) return response('Not authorized', 401);

        return auth()->user()->feed->map(function (Activity $activity) {
            return view('partials.activities.activity')->withActivity($activity)->render();
        });
    }
}
