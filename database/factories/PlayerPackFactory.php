<?php

use Faker\Generator as Faker;
use DGTournaments\Models\PlayerPack;
use DGTournaments\Models\Tournament;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(PlayerPack::class, function (Faker $faker) {
    return [
        'tournament_id' => factory(Tournament::class)->create()->id,
        'title' => $faker->word,
        'description' => $faker->paragraph()
    ];
});
