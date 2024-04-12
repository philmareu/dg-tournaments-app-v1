<?php

use Faker\Generator as Faker;
use DGTournaments\Models\PdgaTier;

$factory->define(PdgaTier::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'code' => $faker->randomLetter . $faker->randomLetter,
    ];
});
