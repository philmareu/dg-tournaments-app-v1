<?php

/*
|--------------------------------------------------------------------------
| PAGES
|--------------------------------------------------------------------------
*/

// Primary
Route::get('/', 'HomeController@index');
Route::get('search', 'SearchController@index')->name('search');

// Tournament
Route::get('disc-golf-tournament/{tournament}/{slug}/{section?}', 'TournamentsController@index');

// Auth
Route::get('auth/facebook', 'Auth\FacebookAuthController@redirectToProvider')->name('auth.facebook');
Route::get('auth/facebook/callback', 'Auth\FacebookAuthController@handleProviderCallback');
Route::get('auth/twitter', 'Auth\TwitterAuthController@redirectToProvider')->name('auth.twitter');
Route::get('auth/twitter/callback', 'Auth\TwitterAuthController@handleProviderCallback');
Auth::routes();
Route::get('user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');

// Basic
Route::get('about', 'PagesController@about');
Route::get('terms-of-service', 'PagesController@page');
Route::get('privacy-policy', 'PagesController@page');
Route::get('contact-us', 'FeedbacksController@index');
Route::post('contact-us', 'FeedbacksController@store');
Route::get('tournament/claim/confirm/{token}', 'User\ClaimController@viewClaim');
Route::post('tournament/claim/confirm/{token}', 'User\ClaimController@processClaim');
Route::get('invite/{code}', 'User\ReferralsController@invite')->middleware('guest');
Route::get('tournament', 'TournamentsController@submit');

// Cart/Checkout
Route::get('checkout', 'CheckoutController@index');

// Blog
Route::get('blog', 'BlogController@index');
Route::get('blog/{year}/{month}/{day}/{slug}', 'BlogController@show');

/*
|--------------------------------------------------------------------------
| ENDPOINTS
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Endpoints'], function() {

    include 'endpoints.php';

});

Route::get('user/current', 'Api\UsersController@current');
Route::get('api/weather/{tournament}', 'Api\WeatherController@tournament');

/*
|--------------------------------------------------------------------------
| ACCOUNT
|--------------------------------------------------------------------------
*/

Route::get('account/profile', 'Account\ProfileController@edit')->name('profile.edit');
Route::put('account/profile', 'Account\ProfileController@update')->name('profile.update');
Route::get('account/settings', 'Account\SettingsController@edit')->name('account.settings');
Route::put('account/settings', 'Account\SettingsController@update')->name('account.settings.update');
Route::get('account/memberships', 'Account\MembershipsController@edit')->name('account.memberships');
Route::put('account/memberships', 'Account\MembershipsController@update')->name('account.memberships.update');
Route::get('account/orders', 'Account\OrdersController@index')->name('account.orders');
Route::get('account/orders/{order}', 'Account\OrdersController@show')->name('account.orders.show');
Route::get('account/billing', 'Account\BillingController@index');
Route::get('account/notifications', 'Account\EmailNotificationsController@index')->name('account.notifications');
Route::put('account/notifications', 'Account\EmailNotificationsController@update');
Route::get('account/referral', 'User\ReferralsController@create')->name('referral.create');
Route::post('account/referral', 'User\ReferralsController@store')->name('referral.store');

/*
|--------------------------------------------------------------------------
| MANAGE
|--------------------------------------------------------------------------
*/

// Manage Tools
Route::get('manage', 'User\TournamentManagerController@index')->name('manage.index');
Route::get('manage/sponsors', 'User\TournamentManagerController@sponsors')->name('manage.sponsor-library');
Route::get('manage/submit', 'User\TournamentManagerController@submit')->name('manage.submit');
Route::get('manage/stripe', 'User\TournamentManagerController@stripe')->name('manage.stripe-accounts');
Route::get('manage/stripe/connect', 'User\TournamentManagerController@connect');

// Manage Tournament
Route::get('manage/{tournament}/order/{transfer}', 'User\ManageTournamentController@order')->name('manage.tournament.order');
Route::get('manage/{tournament}/sponsors', 'User\ManageTournamentController@sponsors')->name('manage.tournament.sponsors');
Route::get('manage/{tournament}/credit-card', 'User\ManageTournamentController@creditCard')->name('manage.tournament.credit-card');
Route::get('manage/{tournament}/orders', 'User\ManageTournamentController@orders')->name('manage.tournament.orders');
Route::get('manage/{tournament}/followers', 'User\ManageTournamentController@followers')->name('manage.tournament.followers');
Route::get('manage/{tournament}/basics', 'User\ManageTournamentController@basics')->name('manage.tournament.basics');
Route::get('manage/{tournament}/course/{tournamentCourse}', 'User\ManageTournamentController@course')->name('manage.tournament.course');
Route::get('manage/{tournament}/sponsorship/{sponsorship}', 'User\ManageTournamentController@sponsorship')->name('manage.tournament.sponsorship');
Route::get('manage/{tournament}/registration', 'User\ManageTournamentController@registration')->name('manage.tournament.registration');
Route::get('manage/{tournament}/schedule', 'User\ManageTournamentController@schedule')->name('manage.tournament.schedule');
Route::get('manage/{tournament}/links', 'User\ManageTournamentController@links')->name('manage.tournament.links');
Route::get('manage/{tournament}/media', 'User\ManageTournamentController@media')->name('manage.tournament.media');
Route::get('manage/{tournament}/player-packs', 'User\ManageTournamentController@playerPacks')->name('manage.tournament.player-packs');
Route::get('manage/{tournament}', 'User\ManageTournamentController@basics')->name('manage.tournament.index');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::get('blog/preview/{id}', 'BlogController@preview')->middleware('admin');

Route::group(['middleware' => 'admin', 'namespace' => 'Admin', 'prefix' => 'admin'], function() {

    Route::get('dashboard', 'DashboardController@index');

    Route::get('tournaments/future', 'TournamentsController@future');
    Route::get('tournaments/flagged', 'TournamentsController@flagged');
    Route::get('sessions', 'SessionsController@index');
    Route::resource('tournaments', 'TournamentsController', ['only' => ['index', 'edit', 'update', 'show']]);
    Route::post('flags/{flag}/postpone/{days}', 'FlagsController@postpone');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

include 'mailable.php';
