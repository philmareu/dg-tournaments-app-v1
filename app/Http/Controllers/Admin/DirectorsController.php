<?php

namespace DGTournaments\Http\Controllers\Admin;

use DGTournaments\Models\Player;
use Illuminate\Http\Request;

use DGTournaments\Http\Requests;
use DGTournaments\Http\Controllers\Controller;

class DirectorsController extends Controller
{
    protected $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function index()
    {
        $directors = $this->player
            ->with('directingTournaments', 'inactiveTournaments')
            ->has('inactiveTournaments')
            ->get();

        return view('admin.directors.index')
            ->with('directors', $directors);
    }

    public function show(Player $player)
    {
        return view('admin.directors.show')
            ->with('director', $player);
    }
}
