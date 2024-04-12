<?php

use Faker\Generator as Faker;
use DGTournaments\Models\OrderSponsorship;
use DGTournaments\Models\Order;
use DGTournaments\Models\Sponsorship;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(OrderSponsorship::class, function (Faker $faker) {
    return [
        'order_id' => factory(Order::class)->create()->id,
        'sponsorship_id' => factory(Sponsorship::class)->create()->id,
        'cost' => $faker->randomNumber(3)
    ];
});
