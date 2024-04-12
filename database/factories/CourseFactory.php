<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Course;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'slug' => $faker->word,
        'address' => $faker->streetAddress,
        'address_2' => $faker->streetAddress,
        'city' => $faker->city,
        'state_province' => $faker->word,
        'country' => $faker->country,
        'description' => $faker->paragraph,
        'directions' => $faker->paragraph,
        'length' => $faker->randomNumber(4),
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude
    ];
});
