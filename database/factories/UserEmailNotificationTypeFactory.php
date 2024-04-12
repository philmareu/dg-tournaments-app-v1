<?php

use Faker\Generator as Faker;
use DGTournaments\Models\User\UserEmailNotificationType;

$factory->define(UserEmailNotificationType::class, function (Faker $faker) {
    return [
        'label' => $faker->name
    ];
});
