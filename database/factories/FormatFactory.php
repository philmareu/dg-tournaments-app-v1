<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Format;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Format::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'code' => $faker->randomLetter,
    ];
});
