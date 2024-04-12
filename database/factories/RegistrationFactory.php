<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Registration;

$factory->define(Registration::class, function (Faker $faker) {
    return [
        'tournament_id' => function() {
            return factory(DGTournaments\Models\Tournament::class)->create()->id;
        },
        'opens_at' => \Carbon\Carbon::now()->subDay(),
        'closes_at' => \Carbon\Carbon::now()->addMonth(),
        'url' => $faker->url
    ];
});
