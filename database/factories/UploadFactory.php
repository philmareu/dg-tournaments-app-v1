<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Upload;

$factory->define(Upload::class, function (Faker $faker) {
    return [
        'filename' => $faker->word,
        'title' => $faker->word,
        'alt' => $faker->word,
        'mime' => $faker->mimeType,
        'size' => $faker->randomNumber(5),
        'user_id' => function() {
            return factory(DGTournaments\Models\User\User::class)->create()->id;
        }
    ];
});
