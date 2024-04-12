<?php

use Faker\Generator as Faker;
use DGTournaments\Models\SpecialEventType;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(SpecialEventType::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'slug' => $faker->word
    ];
});
