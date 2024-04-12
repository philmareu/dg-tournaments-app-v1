<?php

use Illuminate\Support\Facades\Route;

Route::post('manage/submit', 'TournamentsEndpointController@store');

/*
|--------------------------------------------------------------------------
| /lists
|--------------------------------------------------------------------------
*/

// Special Event Types
Route::get('lists/special-event-types', 'SpecialEventTypesEndpointController@list');

/*
|--------------------------------------------------------------------------
| /tournament
|--------------------------------------------------------------------------
*/

// Poster
Route::put('tournament/poster/{tournament}', 'Tournament\TournamentPosterEndpointController@update');
Route::delete('tournament/poster/{tournament}', 'Tournament\TournamentPosterEndpointController@destroy');

// Stripe
Route::put('tournament/stripe/{tournament}', 'Tournament\TournamentStripeAccountEndpointController@update');
Route::delete('tournament/stripe/{tournament}', 'Tournament\TournamentStripeAccountEndpointController@destroy');

// Registration
Route::post('tournament/registration/{tournament}', 'RegistrationEndpointController@store');
Route::put('tournament/registration/{registration}', 'RegistrationEndpointController@update');

// Surrounding Courses
Route::get('tournament/surrounding-courses/{tournament}', 'Tournament\TournamentSurroundingCoursesEndpointController@get');

// Sponsorships
Route::post('tournament/sponsorships/{tournament}', 'SponsorshipsEndpointController@store');
Route::put('tournament/sponsorships/{sponsorship}', 'SponsorshipsEndpointController@update');
Route::delete('tournament/sponsorships/{sponsorship}', 'SponsorshipsEndpointController@destroy');
//    Route::get('sponsors/{tournament}', 'SponsorsController@index');

// Tournament Sponsors
Route::post('tournament/sponsorship/sponsors/{sponsorship}', 'TournamentSponsorsEndpointController@store');
Route::put('tournament/sponsorship/sponsors/{tournamentSponsor}', 'TournamentSponsorsEndpointController@update');
Route::delete('tournament/sponsorship/sponsors/{tournamentSponsor}', 'TournamentSponsorsEndpointController@destroy');

// Player Packs
Route::post('tournament/player-packs/{tournament}', 'PlayerPacksEndpointController@store');
Route::put('tournament/player-packs/{playerPack}', 'PlayerPacksEndpointController@update');
Route::delete('tournament/player-packs/{playerPack}', 'PlayerPacksEndpointController@destroy');

// Player Packs Items
Route::post('tournament/player-pack/items/{playerPack}', 'PlayerPackItemsEndpointController@store');
Route::put('tournament/player-pack/items/{playerPackItem}', 'PlayerPackItemsEndpointController@update');
Route::delete('tournament/player-pack/items/{playerPackItem}', 'PlayerPackItemsEndpointController@destroy');

// Course
Route::get('tournament/courses/{tournamentCourse}', 'TournamentCoursesEndpointController@show');
Route::post('tournament/courses/{tournament}', 'TournamentCoursesEndpointController@store');
Route::put('tournament/courses/{tournamentCourse}', 'TournamentCoursesEndpointController@update');
Route::delete('tournament/courses/{tournamentCourse}', 'TournamentCoursesEndpointController@destroy');
Route::put('tournament/course/holes/{tournamentCourse}', 'TournamentCoursesEndpointController@holes');

// Schedule
Route::get('tournament/schedule/{tournament}', 'ScheduleEndpointController@get');
Route::post('tournament/schedule/{tournament}', 'ScheduleEndpointController@store');
Route::put('tournament/schedule/{schedule}', 'ScheduleEndpointController@update');
Route::delete('tournament/schedule/{schedule}', 'ScheduleEndpointController@destroy');

// Link
Route::post('tournament/links/{tournament}', 'LinksEndpointController@store');
Route::put('tournament/links/{link}', 'LinksEndpointController@update');
Route::delete('tournament/links/{link}', 'LinksEndpointController@destroy');

// Media
Route::post('tournament/media/{tournament}', 'MediaEndpointController@store');
Route::put('tournament/media/{tournament}', 'MediaEndpointController@update');
Route::delete('tournament/media/{tournament}/{upload_id}', 'MediaEndpointController@destroy');

// Claim
Route::post('tournament/claim/{tournament}', 'UserClaimEndpointController@claim');

// Basic
Route::get('tournament/{tournament}', 'TournamentsEndpointController@index');
Route::post('tournament', 'TournamentsEndpointController@store');
Route::put('tournament/{tournament}', 'TournamentsEndpointController@update');

/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/

// Sponsors
Route::get('user/sponsors', 'UserSponsorsEndpointController@list');
Route::post('user/sponsors', 'UserSponsorsEndpointController@store');
Route::put('user/sponsors/{sponsor}', 'UserSponsorsEndpointController@update');
Route::delete('user/sponsors/{sponsor}', 'UserSponsorsEndpointController@destroy');

// Follow
Route::put('user/follow/tournament/{tournament}', 'UserFollowsEndpointController@tournament');

// Uploads
Route::post('user/upload', 'UserFilesEndpointController@upload');

// Payment Methods
Route::get('user/credit-cards', 'UserEndpointController@getCards');
Route::post('user/stripe/customer', 'UserEndpointController@createCustomer');
Route::post('user/stripe/card', 'UserEndpointController@addCard');
Route::delete('user/stripe/card/{cardId}', 'UserEndpointController@removeCard');

// Search
Route::post('user/searches', 'UserSearchEndpointController@store');
Route::put('user/searches/{search}', 'UserSearchEndpointController@update');
Route::delete('user/searches/{search}', 'UserSearchEndpointController@destroy');

// Stripe
Route::delete('user/stripe/{stripeAccount}', 'UserStripeEndpointController@destroy');

// Feed
Route::get('user/feed', 'UserFeedController@index');

/*
|--------------------------------------------------------------------------
| ORDER
|--------------------------------------------------------------------------
*/

Route::get('order/current', 'OrderCurrentEndpointController');
Route::put('order/sponsorships/{sponsorship}', 'OrderEndpointController@addSponsorship');
Route::delete('order/sponsorships/{orderSponsorship}', 'OrderEndpointController@destroySponsorship');

// Checkout
Route::put('order/checkout/details', 'OrderEndpointController@processDetails');
Route::post('order/checkout/pay', 'OrderEndpointController@pay');

Route::post('order/refund/{transfer}', 'ManageOrderEndpointController@refund');

/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

Route::get('cache/bounds/{map?}', 'MapBoundsEndpointController@getBounds');
Route::put('cache/bounds/{map?}', 'MapBoundsEndpointController@setBounds');