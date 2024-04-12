<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Order;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Order::class, function (Faker $faker) {
    return [
        'unique' => str_random(100),
        'email' => $faker->email,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'user_id' => factory(DGTournaments\Models\User\User::class)->create()->id
    ];
});

$factory->state(Order::class, 'paid', [
    'paid' => 1
]);
