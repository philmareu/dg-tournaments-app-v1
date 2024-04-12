<?php

use Faker\Generator as Faker;
use DGTournaments\Models\StripeAccount;
use DGTournaments\Models\User\User;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(StripeAccount::class, function (Faker $faker) {
    return [
        'display_name' => $faker->word,
        'access_token' => $faker->word,
        'stripe_user_id' => $faker->word,
        'user_id' => factory(User::class)->create()
    ];
});
