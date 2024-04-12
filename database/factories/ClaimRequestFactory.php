<?php

use DGTournaments\Models\ClaimRequest;
use DGTournaments\Models\User\User;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(ClaimRequest::class, function (Faker $faker) {
    return [
        'token' => $faker->word,
        'user_id' => function() {
            return factory(User::class)->create()->id;
        }
    ];
});
