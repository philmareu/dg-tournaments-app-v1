<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use DGTournaments\Models\Search;
use DGTournaments\Models\User\User;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Search::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'query' => $faker->word,
        'wants_notification' => $faker->boolean(),
        'user_id' => factory(User::class)->create()
    ];
});

$factory->state(Search::class, 'daily-not-ready', [
    'wants_notification' => 1,
    'frequency' => 'daily',
    'searched_at' => Carbon::now()->subHours(12)
]);

$factory->state(Search::class, 'weekly-not-ready', [
    'wants_notification' => 1,
    'frequency' => 'weekly',
    'searched_at' => Carbon::now()->subDays(3)
]);

$factory->state(Search::class, 'daily-ready', [
    'wants_notification' => 1,
    'frequency' => 'daily',
    'searched_at' => Carbon::now()->subHours(26)
]);

$factory->state(Search::class, 'weekly-ready', [
    'wants_notification' => 1,
    'frequency' => 'weekly',
    'searched_at' => Carbon::now()->subDays(8)
]);
