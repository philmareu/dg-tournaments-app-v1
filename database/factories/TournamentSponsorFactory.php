<?php

use Faker\Generator as Faker;
use DGTournaments\Models\TournamentSponsor;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\Sponsor;
use DGTournaments\Models\Sponsorship;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(TournamentSponsor::class, function (Faker $faker) {
    return [
        'tournament_id' => factory(Tournament::class)->create()->id,
        'sponsor_id' => factory(Sponsor::class)->create()->id,
        'sponsorship_id' => factory(Sponsorship::class)->create()->id
    ];
});
