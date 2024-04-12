<?php

use Faker\Generator as Faker;
use DGTournaments\Models\Sponsor;
use DGTournaments\Models\Upload;
use DGTournaments\Models\User\User;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Sponsor::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'url' => $faker->url,
        'upload_id' => factory(Upload::class)->create()->id,
        'user_id' => factory(User::class)->create()->id
    ];
});

//$factory->define(DGTournaments\Models\Sponsor::class, function (Faker\Generator $faker) {
//    return [
//        'title' => $faker->word,
//        'url' => $faker->url,
//        'upload_id' => factory(\DGTournaments\Models\Upload::class)->create()->id,
//        'user_id' => factory(\DGTournaments\Models\User\User::class)->create()->id
//    ];
//});
