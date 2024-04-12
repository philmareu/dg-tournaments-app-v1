<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Tournament;

$factory->define(Tournament::class, function (Faker $faker) {

    $date = \Carbon\Carbon::now()->addDays(rand(1, 60));

    return [
        'name' => $faker->word,
        'slug' => $faker->word,
        'city' => $faker->city,
        'state_province' => 'Kansas',
        'country' => $faker->country,
        'start' => $date,
        'end' => $date->addDay(),
        'authorization_email' => $faker->email,
        'director' => $faker->name,
        'phone' => $faker->phoneNumber,
        'latitude' => $faker->randomFloat(6, 30, 49),
        'longitude' => $faker->randomFloat(6, -124, -74),
        'description' => $faker->sentence,
        'poster_id' => null,
        'format_id' => factory(\DGTournaments\Models\Format::class)->create()->id
    ];
});

$factory->state(Tournament::class, 'no-geo', [
    'latitude' => null,
    'longitude' => null
]);

$date = \Carbon\Carbon::now()->addDays(rand(1, 60));

$factory->state(Tournament::class, 'future', [
    'start' => $date,
    'end' => $date->addDay()
]);
