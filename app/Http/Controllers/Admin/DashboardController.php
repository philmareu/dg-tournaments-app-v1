<?php

namespace DGTournaments\Http\Controllers\Admin;

use DGTournaments\Models\Activity;
use DGTournaments\Models\Follow;
use DGTournaments\Models\Order;
use DGTournaments\Models\Search;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\StripeAccount;
use DGTournaments\Models\TournamentSponsor;
use DGTournaments\Models\User\User;
use Illuminate\Http\Request;

use DGTournaments\Http\Requests;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // # users
    // # sponsorships
    // # saved searches

    public function index()
    {
        return view('admin.dashboard')
            ->withCounts([
                [
                    'title' => 'Users',
                    'quantity' => User::count()
                ],
                [
                    'title' => 'Searches',
                    'quantity' => Search::count()
                ],
                [
                    'title' => 'Sponsorships',
                    'quantity' => Sponsorship::count()
                ],
                [
                    'title' => 'Follows',
                    'quantity' => Follow::count()
                ],
                [
                    'title' => 'Managers',
                    'quantity' => DB::table('managers')->count()
                ],
                [
                    'title' => 'Orders',
                    'quantity' => Order::count()
                ],
                [
                    'title' => 'Stripe Accounts',
                    'quantity' => StripeAccount::count()
                ],
                [
                    'title' => 'Tournament Sponsors',
                    'quantity' => TournamentSponsor::count()
                ]
            ]);
    }
}
