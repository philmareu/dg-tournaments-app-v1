<?php

use DGTournaments\Models\FlagType;
use Faker\Generator as Faker;

$factory->define(FlagType::class, function (Faker $faker) {
    return [
        'title' => $faker->word
    ];
});
