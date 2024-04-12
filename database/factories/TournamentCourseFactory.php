<?php

use Faker\Generator as Faker;
use DGTournaments\Models\TournamentCourse;
use DGTournaments\Models\Course;
use DGTournaments\Models\Tournament;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(TournamentCourse::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'address' => $faker->streetAddress,
        'address_2' => $faker->streetAddress,
        'city' => $faker->city,
        'state_province' => $faker->word,
        'country' => $faker->country,
        'notes' => $faker->paragraph,
        'directions' => $faker->paragraph,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'holes' => $faker->randomElement([9, 18, 27]),
        'course_id' => factory(Course::class)->create()->id,
        'tournament_id' => factory(Tournament::class)->create()->id
    ];
});
