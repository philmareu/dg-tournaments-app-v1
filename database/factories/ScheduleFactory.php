<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Schedule;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Schedule::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTime(),
        'start' => $faker->dateTime(),
        'end' => $faker->dateTime(),
        'summary' => $faker->sentence,
        'location' => $faker->city,
        'tournament_id' => factory(\DGTournaments\Models\Tournament::class)->create()->id
    ];
});
