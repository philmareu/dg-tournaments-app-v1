<?php

use Faker\Generator as Faker;
use DGTournaments\Models\PlayerPackItem;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(PlayerPackItem::class, function (Faker $faker) {
    return [
        'title' => $faker->word
    ];
});
