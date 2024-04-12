<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\Tournament;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Sponsorship::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'tier' => $faker->numberBetween(1, 5),
        'quantity' => $faker->randomNumber(1),
        'cost' => $faker->numberBetween(10, 100),
        'description' => $faker->paragraph,
        'tournament_id' => factory(Tournament::class)->create()->id,
    ];
});
