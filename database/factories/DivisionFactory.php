<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Division;

$factory->define(Division::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'code' => $faker->word,
        'class_id' => function() {
            return factory(DGTournaments\Models\Classes::class)->create()->id;
        }
    ];
});
