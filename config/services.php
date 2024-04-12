<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_ID'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect' => env('FACEBOOK_URL'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_ID'),
        'client_secret' => env('TWITTER_SECRET'),
        'redirect' => env('TWITTER_URL'),
    ],

    'stripe' => [
        'model' => DGTournaments\Models\User\User::class,
        'key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
        'client_id' => env('STRIPE_CLIENT_ID')
    ],

    'pdga' => [
        'username' => env('PDGA_USERNAME'),
        'secret' => env('PDGA_SECRET'),
        'from' => env('TOURNAMENT_DATA_SOURCE_FROM_DATE'),
        'to' => env('TOURNAMENT_DATA_SOURCE_TO_DATE')
    ],

    'foursquare' => [
        'clientId' => env('FOURSQUARE_CLIENT_ID'),
        'clientSecret' => env('FOURSQUARE_SECRET')
    ],

    'darksky' => [
        'secret' => env('DARKSKY_SECRET')
    ],

    'algolia' => [
        'appId' => env('ALGOLIA_APP_ID'),
        'searchKey' => env('ALGOLIA_SEARCH_KEY'),
        'secret' => env('ALGOLIA_SECRET')
    ],

    'mapbox' => [
        'token' => env('MAPBOX_TOKEN')
    ]

];
