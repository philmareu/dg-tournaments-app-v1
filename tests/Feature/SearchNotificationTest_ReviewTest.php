<?php

namespace Tests\Feature;

use Carbon\Carbon;
use DGTournaments\Helpers\AlgoliaQueryStringBuilder;
use DGTournaments\Mail\User\SavedSearchFoundNewTournamentsMailable;
use DGTournaments\Models\Classes;
use DGTournaments\Models\Course;
use DGTournaments\Models\PdgaTier;
use DGTournaments\Models\Search;
use DGTournaments\Models\SpecialEventType;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use DGTournaments\Repositories\ActivationRepository;
use DGTournaments\Repositories\TournamentRepository;
use DGTournaments\Services\Auth\UserActivation;
use DGTournaments\Repositories\SearchRepository;
use DGTournaments\Repositories\UserRepository;
use DGTournaments\Services\DarkSky\DarkSkyApi;
use DGTournaments\Services\Foursquare\FoursquareApi;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchNotificationTest_ReviewTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }

    // need several users

    // get a list of notifications that wants_notification

    // Go through the list and determine if there are any tournaments that find the search parameters. Attach this list to the user_id.

    // Get list of all users with the ids

    // Send notifications

    /*************************************/

    // We need one user and a search query, than build several tournaments that fit and don't fit the criteria

    /**
     * @test
     */
    public function should_return_a_list_of_daily_searches_that_are_ready_to_search_for_new_tournaments()
    {
        $repo = $this->getSearchRepo();

        $ready = factory(Search::class, 2)->states('daily-ready')->create();

        $repo->getReadyByFrequency('daily')->each(function (Search $search) use ($ready) {
            $this->assertTrue($ready->where('id', $search->id)->isNotEmpty());
        });
    }

    /**
     * @test
     */
    public function should_return_an_empty_list_of_daily_searches()
    {
        $repo = $this->getSearchRepo();

        factory(Search::class, 2)->states('daily-not-ready')->create();

        $this->assertTrue($repo->getReadyByFrequency('daily')->isEmpty());
    }

    /**
     * @test
     */
    public function should_return_a_list_of_weekly_searches_that_are_ready_to_search_for_new_tournaments()
    {
        $repo = $this->getSearchRepo();

        $ready = factory(Search::class, 2)->states('weekly-ready')->create();

        $repo->getReadyByFrequency('weekly')->each(function (Search $search) use ($ready) {
            $this->assertTrue($ready->where('id', $search->id)->isNotEmpty());
        });
    }

    /**
     * @test
     */
    public function should_return_an_empty_list_of_weekly_searches()
    {
        $repo = $this->getSearchRepo();

        factory(Search::class, 2)->states('weekly-not-ready')->create();

        $this->assertTrue($repo->getReadyByFrequency('weekly')->isEmpty());
    }

    /**
     * @test
     */
    public function should_return_a_combined_list_of_ready_searches_for_all_frequencies()
    {
        $repo = $this->getSearchRepo();

        $ready = factory(Search::class, 3)->states('daily-ready')->create();
        $ready = $ready->merge(factory(Search::class, 3)->states('weekly-ready')->create());

        $this->assertEquals(6, $repo->getAllReadySearches()->count());

        $repo->getAllReadySearches()->each(function (Search $search) use ($ready) {
            $this->assertTrue($ready->where('id', $search->id)->isNotEmpty());
        });
    }

    /**
     * @test
     */
    public function should_return_a_list_of_ready_searches_formatted_for_notifications()
    {
        $repo = $this->getSearchRepo();

        $user1 = factory(User::class)->create(['id' => 124]);
        $user2 = factory(User::class)->create(['id' => 3492]);

        $ready1 = factory(Search::class, 2)->states('daily-ready')->create();
        $ready2 = factory(Search::class, 2)->states('daily-ready')->create();

        $user1->searches()->saveMany($ready1);
        $user2->searches()->saveMany($ready2);

        $searches = $repo->getReadySearchesForNotifications();

        $this->assertTrue(isset($searches[$user1->id]));
        $this->assertEquals(2, $searches[$user1->id]['searches']->count());

        $this->assertTrue(isset($searches[$user2->id]));
        $this->assertEquals(2, $searches[$user2->id]['searches']->count());
    }

    /**
     * @test
     */
    public function should_return_a_list_of_tournaments_that_meet_search_criteria()
    {
        $this->seed('FormatsTableSeeder');
        $this->seed('ClassesTableSeeder');
        $this->seed('PdgaTierSeeder');
        $this->seed('SpecialEventTypesSeeder');

        $urlBuilder = new AlgoliaQueryStringBuilder;
        $repo = $this->getSearchRepo();
        $user = $this->createUser();
        $search = factory(Search::class)->create([
            'query' => $urlBuilder->setIndex('tournaments')
                ->setGeo(39.205937718846, -95.39560757959067, 34.191386278974974, -101.96981718594925)
                ->setFormat(['Singles'])
                ->setClasses(['Pro', 'Am'])
                ->setPdgaTier(['M', 'NT'])
                ->setEarliestDate(Carbon::createFromDate(2017, 10, 1))
                ->setLatestDate(Carbon::createFromDate(2017, 12, 10))
                ->setSpecialEventTypes(['Women\'s Only'])
                ->setSanctioned(['PDGA'])
                ->buildQueryString()
        ]);

        $user->searches()->save($search);

        // In search
        $tournament1 = factory(Tournament::class)->create([
            'latitude' => 38.8,
            'longitude' => -96.3,
            'format_id' => 1,
            'start' => Carbon::createFromDate(2017, 10, 8)
        ]);

        $tournament1->classes()->save(Classes::where('title', 'Pro')->first());
        $tournament1->classes()->save(Classes::where('title', 'Am')->first());
        $tournament1->pdgaTiers()->save(PdgaTier::where('code', 'M')->first());
        $tournament1->pdgaTiers()->save(PdgaTier::where('code', 'NT')->first());
        $tournament1->specialEventTypes()->save(SpecialEventType::where('title', 'Women\'s Only')->first());

        // Not in search
        $tournament2 = factory(Tournament::class)->create([
            'latitude' => 40,
            'longitude' => -96.3,
            'format_id' => 2,
            'start' => Carbon::createFromDate(2017, 9, 2)
        ]);

        $tournament2->classes()->save(Classes::where('title', 'Pro')->first());
        $tournament2->pdgaTiers()->save(PdgaTier::where('code', 'M')->first());
        $tournament2->specialEventTypes()->save(SpecialEventType::where('title', 'Women\'s Only')->first());

        $tournaments = $repo->findTournamentBySearchQuery($search);

        $this->assertEquals(1, $tournaments->count());
        $this->assertEquals($tournament1->id, $tournament1->first()->id);
    }

    /**
     * @test
     */
    public function should_return_a_list_with_user_and_the_unique_tournaments_that_meet_filter_requirements()
    {
        $this->baseSeeds();
        $repo = $this->getSearchRepo();
        $user = $this->createUser();

        list($search1, $tournament) = $this->buildReadySearchWithMatchingTournament();
        list($search2, $tournament) = $this->buildReadySearchWithMatchingTournament();

        $user->searches()->save($search1);
        $user->searches()->save($search2);

        $tournamentNotifications = $repo->getTournamentNotifications();

        $this->assertEquals(1, $tournamentNotifications->first()['tournaments']->first()->id);
    }

    /**
     * @test
     */
    public function tournaments_must_be_created_within_the_frequency_to_show_up_in_notifications()
    {
        $this->baseSeeds();
        $repo = $this->getSearchRepo();
        $user = $this->createUser();

        list($search1, $tournament) = $this->buildReadySearchWithMatchingTournament();

        $tournament->created_at = Carbon::now()->subDays(8);
        $tournament->save();

        $user->searches()->save($search1);

        $tournamentNotifications = $repo->getTournamentNotifications();

        $this->assertTrue($tournamentNotifications->isEmpty());
    }

    /**
     * @test
     */
    public function should_send_a_email_to_the_user_about_new_tournaments()
    {
        $this->baseSeeds();
        $repo = $this->getSearchRepo();
        $user = $this->createUser();

        list($search1, $tournament) = $this->buildReadySearchWithMatchingTournament();
        list($search2, $tournament) = $this->buildReadySearchWithMatchingTournament();

        $user->searches()->save($search1);
        $user->searches()->save($search2);

        Mail::fake();

        Artisan::call('search:check-saved-searches');

        Mail::assertQueued(SavedSearchFoundNewTournamentsMailable::class);
    }

    /**
     * @test
     */
    public function should_mark_the_searched_at_field_after_notification_has_been_sent()
    {
        $this->baseSeeds();
        $repo = $this->getSearchRepo();
        $user = $this->createUser();

        list($search1, $tournament) = $this->buildReadySearchWithMatchingTournament();
        list($search2, $tournament) = $this->buildReadySearchWithMatchingTournament();

        $user->searches()->save($search1);
        $user->searches()->save($search2);

        $repo->getTournamentNotifications();

        $oldDate1 = $search1->searched_at;
        $oldDate2 = $search2->searched_at;

        Artisan::call('search:check-saved-searches');

        $this->assertNotEquals($oldDate1->format('U'), $search1->fresh()->searched_at->format('U'));
        $this->assertNotEquals($oldDate2->format('U'), $search2->fresh()->searched_at->format('U'));
    }

    /**
     * @test
     */
    public function tournament_notifications_should_be_empty_once_they_are_done()
    {
        $this->baseSeeds();
        $repo = $this->getSearchRepo();
        $user = $this->createUser();

        list($search1, $tournament) = $this->buildReadySearchWithMatchingTournament();

        $user->searches()->save($search1);

        Mail::fake();

        Artisan::call('search:check-saved-searches');

        $tournamentNotifications = $repo->getTournamentNotifications();

        $this->assertTrue($tournamentNotifications->isEmpty());
    }

    private function getSearchRepo()
    {
        return new SearchRepository(new Search, new Tournament);
    }

    public function baseSeeds()
    {
        $this->seed('FormatsTableSeeder');
        $this->seed('ClassesTableSeeder');
        $this->seed('PdgaTierSeeder');
        $this->seed('SpecialEventTypesSeeder');
    }

    private function getUserRepo()
    {
        return new UserRepository(new User, new UserActivation(new ActivationRepository(new \DGTournaments\Models\User\UserActivation)));
    }

    protected function getTournamentRepo()
    {
        return new TournamentRepository(
            new Tournament,
            new FoursquareApi,
            new DarkSkyApi,
            new Course
        );
    }

    /**
     * Just a helper
     *
     * @param $url
     */
    private function parseUrl($url)
    {
        parse_str(urldecode(parse_url($url)['query']), $result);
    }

    private function buildReadySearchWithMatchingTournament()
    {
        $urlBuilder = new AlgoliaQueryStringBuilder;

        $search = factory(Search::class)->states('daily-ready')->create([
            'query' => $urlBuilder->setIndex('tournaments')
                ->setGeo(39.205937718846, -95.39560757959067, 34.191386278974974, -101.96981718594925)
                ->setFormat(['Singles'])
                ->setClasses(['Pro', 'Am'])
                ->setPdgaTier(['M', 'NT'])
                ->setEarliestDate(Carbon::createFromDate(2017, 12, 1))
                ->setLatestDate(Carbon::createFromDate(2017, 12, 10))
                ->setSpecialEventTypes(['Women\'s Only'])
                ->setSanctioned(['PDGA'])
                ->buildQueryString()
        ]);

        // In search
        $tournament = factory(Tournament::class)->create([
            'latitude' => 38.8,
            'longitude' => -96.3,
            'format_id' => 1,
            'start' => Carbon::createFromDate(2017, 12, 8)
        ]);

        $tournament->classes()->save(Classes::where('title', 'Pro')->first());
        $tournament->classes()->save(Classes::where('title', 'Am')->first());
        $tournament->pdgaTiers()->save(PdgaTier::where('code', 'M')->first());
        $tournament->pdgaTiers()->save(PdgaTier::where('code', 'NT')->first());
        $tournament->specialEventTypes()->save(SpecialEventType::where('title', 'Women\'s Only')->first());

        return [$search, $tournament];
    }
}
