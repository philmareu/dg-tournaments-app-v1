<?php

namespace DGTournaments\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
//        dd([
//            'south' => $request->cookie('map-search-south') ?: 35.786085633851,
//            'north' => $request->cookie('map-search-north') ?: 38,
//            'west' => $request->cookie('map-search-west') ?: -115,
//            'east' => $request->cookie('map-search-east') ?: -105
//        ]);

        return view('pages.search.tournaments');
    }

    public function staff()
    {
        return view('pages.search.staff');
    }

    public function sponsorships()
    {
        return view('pages.search.sponsorships');
    }
}
