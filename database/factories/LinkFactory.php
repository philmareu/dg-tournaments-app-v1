<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Link;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Link::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'url' => $faker->url,
        'ordinal' => 1,
        'tournament_id' => factory(\DGTournaments\Models\Tournament::class)->create()->id
    ];
});
