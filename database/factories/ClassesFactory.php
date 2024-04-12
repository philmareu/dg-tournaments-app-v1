<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Classes;

$factory->define(Classes::class, function (Faker $faker) {
    return [
        'title' => $faker->word
    ];
});
