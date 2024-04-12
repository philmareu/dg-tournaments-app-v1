<?php

//ONLINE=true
//LOAD_STRIPE=false
//LOAD_FONTS=true
//LOAD_MAPBOX=true
//LOAD_GOOGLE_ANALYTICS=false
//SCOUT_DRIVER=null

return [

    /*
    |--------------------------------------------------------------------------
    | Big switch
    |--------------------------------------------------------------------------
    |
    |
    */

    'online' => env('ONLINE', true),

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    |
    |
    */

    'load' => [
        'stripe' => env('LOAD_STRIPE', true),
        'fonts' => env('LOAD_FONTS', true),
        'mapbox' => env('LOAD_MAPBOX', true),
        'google_analytics' => env('LOAD_GOOGLE_ANALYTICS', true)
    ]
];