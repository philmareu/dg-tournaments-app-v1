<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Feedback;

$factory->define(Feedback::class, function (Faker $faker) {
    return [
        'email' => $faker->safeEmail,
        'feedback' => $faker->paragraph
    ];
});
