<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Follow;

$factory->define(Follow::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(DGTournaments\Models\User\User::class)->create()->id;
        }
    ];
});
