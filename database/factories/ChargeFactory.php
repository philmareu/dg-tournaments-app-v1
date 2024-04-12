<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Charge;
use DGTournaments\Models\Order;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Charge::class, function (Faker $faker) {
    return [
        'charge_id' => $faker->word,
        'status' => $faker->word,
        'amount' => $faker->numberBetween(100, 1000),
        'order_id' => factory(Order::class)->create()
    ];
});

$factory->state(Charge::class, 'successful', [
    'status' => 'succeeded'
]);
