<?php

use Faker\Generator as Faker;
use DGTournaments\Models\DataSource;

$factory->define(DataSource::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'slug' => $faker->word,
        'api_class' => $faker->word,
        'type' => $faker->randomElement(['tournament', 'course'])
    ];
});
