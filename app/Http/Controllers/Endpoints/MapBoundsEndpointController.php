<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class MapBoundsEndpointController extends Controller
{
    public function getBounds(Request $request)
    {
        return [
            'south' => $request->cookie('map-search-south') ?: 35.786085633851,
            'north' => $request->cookie('map-search-north') ?: 38,
            'west' => $request->cookie('map-search-west') ?: -115,
            'east' => $request->cookie('map-search-east') ?: -105
        ];
    }

    public function setBounds(Request $request)
    {
        return response('Set Bounds')
            ->cookie('map-search-south', $request->south)
            ->cookie('map-search-north', $request->north)
            ->cookie('map-search-west', $request->west)
            ->cookie('map-search-east', $request->east);
    }
}
