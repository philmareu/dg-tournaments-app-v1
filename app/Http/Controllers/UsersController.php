<?php

namespace DGTournaments\Http\Controllers\Api;

use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function current()
    {
        if(is_null(auth()->user())) return null;

        return auth()->user()->load('managing', 'followingTournaments.resource.poster', 'stripeAccounts', 'sponsors', 'searches', 'image');
    }
}
