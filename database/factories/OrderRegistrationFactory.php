<?php

use Faker\Generator as Faker;
use DGTournaments\Models\OrderRegistration;

$factory->define(OrderRegistration::class, function (Faker $faker) {
    return [
        'order_id' => function() {
            return factory(DGTournaments\Models\Order::class)->create()->id;
        },
        'registration_id' => function() {
            return factory(DGTournaments\Models\Registration::class)->create()->id;
        },
        'cost' => $faker->randomNumber(4)
    ];
});
