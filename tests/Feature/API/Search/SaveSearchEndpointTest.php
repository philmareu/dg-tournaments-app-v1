<?php

namespace Tests\Feature\Search;

use Carbon\Carbon;
use DGTournaments\Helpers\AlgoliaQuery;
use DGTournaments\Models\Search;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ValidationHelperTrait;

class SaveSearchEndpointTest extends TestCase
{
    use RefreshDatabase, ValidationHelperTrait;

    protected $endpoint = 'user/searches';

    protected $testUrl = 'https://dgtournaments.dev/?q=&idx=tournaments&p=0&nR%5Blatitude%5D%5B%3C%5D%5B0%5D=70.03418321204308&nR%5Blatitude%5D%5B%3E%5D%5B0%5D=-19.472449961980956&nR%5Blongitude%5D%5B%3C%5D%5B0%5D=-14.850579638329123&nR%5Blongitude%5D%5B%3E%5D%5B0%5D=-140.26537037249648';

    /**
     * @test
     */
    public function guest_can_not_save_a_search()
    {
        $this->json('POST', $this->endpoint)
            ->assertStatus(401);
    }

    // Validation

    /**
     * @test
     */
    public function storing_a_search_requires_a_title()
    {
        $this->postValidationTest($this->endpoint, 'title');
    }

    /**
     * @test
     */
    public function storing_a_search_requires_a_url()
    {
        $this->postValidationTest($this->endpoint, 'url');
    }

    /**
     * @test
     */
    public function storing_a_search_requires_a_frequency_if_wants_notification_is_selected()
    {
        $this->postValidationTest($this->endpoint, 'frequency', ['wants_notification' => 1]);
    }

    /**
     * @test
     */
    public function storing_a_search_the_notification_option_must_be_a_boolean()
    {
        $this->postValidationTest($this->endpoint, 'wants_notification', ['wants_notification' => 'not a boolean']);
    }

    /**
     * @test
     */
    public function storing_a_search_the_frequency_must_be_daily_or_weekly()
    {
        $this->postValidationTest($this->endpoint, 'frequency', ['frequency' => 'not an option', 'wants_notification']);

        $this->actingAs($this->createUser())
            ->call('POST', $this->endpoint, ['frequency' => 'daily', 'wants_notification'])
            ->assertSessionMissing('frequency');

        $this->actingAs($this->createUser())
            ->call('POST', $this->endpoint, ['frequency' => 'weekly', 'wants_notification'])
            ->assertSessionMissing('frequency');
    }

    /**
     * @test
     */
    public function storing_a_frequency_is_not_required_if_wants_notification_is_not_selected()
    {
        $this->actingAs($this->createUser())
            ->call('POST', $this->endpoint)
            ->assertSessionMissing('frequency');
    }

    /**
     * @test
     */
    public function updating_a_search_requires_a_title()
    {
        $user = $this->createUser();
        $search = factory(Search::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->call('PUT', $this->endpoint . '/' . $search->id)
            ->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function updating_a_search_the_notification_option_must_be_a_boolean()
    {
        $user = $this->createUser();
        $search = factory(Search::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->call('PUT', $this->endpoint . '/' . $search->id, ['wants_notification' => 'not a boolean'])
            ->assertSessionHasErrors('wants_notification');
    }

    /**
     * @test
     */
    public function updating_a_search_requires_a_frequency_is_wants_notification_is_selected()
    {
        $user = $this->createUser();
        $search = factory(Search::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->call('PUT', $this->endpoint . '/' . $search->id, ['wants_notification' => 1])
            ->assertSessionHasErrors('frequency');
    }

    /**
     * @test
     */
    public function updating_a_frequency_is_not_required_if_wants_notification_is_not_selected()
    {
        $user = $this->createUser();
        $search = factory(Search::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->call('PUT', $this->endpoint . '/' . $search->id, ['wants_notification' => 1])
            ->assertSessionMissing('frequency');
    }

    /**
     * @test
     */
    public function updating_a_search_the_frequency_must_be_daily_or_weekly()
    {
        $user = $this->createUser();
        $search = factory(Search::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->call('PUT', $this->endpoint . '/' . $search->id, ['frequency' => 'not an option', 'wants_notification'])
            ->assertSessionHasErrors('frequency');

        $this->actingAs($user)
            ->call('PUT', $this->endpoint . '/' . $search->id, ['frequency' => 'daily', 'wants_notification'])
            ->assertSessionMissing('frequency');

        $this->actingAs($user)
            ->call('PUT', $this->endpoint . '/' . $search->id, ['frequency' => 'weekly', 'wants_notification'])
            ->assertSessionMissing('frequency');
    }

    /**
     * @test
     */
    public function searched_at_field_is_set_to_current_date_when_created()
    {
        $data = [
            'title' => 'My Saved Search',
            'url' => $this->testUrl,
            'wants_notification' => 1,
            'frequency' => 'daily'
        ];

        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->json('POST', $this->endpoint, $data);

        $search = Search::where('user_id', $user->id)->where('title', $data['title'])->first();

        $this->assertNotNull($search->searched_at);
        $this->assertInstanceOf(Carbon::class, $search->searched_at);
        $this->assertTrue($search->searched_at->diffInSeconds(Carbon::now()) < 60);
    }

    /**
     * @test
     */
    public function map_latitude_and_longitude_is_stored_when_search_is_stored()
    {
        $data = [
            'title' => 'My Saved Search',
            'url' => $this->testUrl,
            'wants_notification' => 1,
            'frequency' => 'daily'
        ];

        $user = $this->createUser();

        $this->actingAs($user)
            ->json('POST', $this->endpoint, $data);

        $this->assertDatabaseHas('searches', [
            'user_id' => $user->id,
            'title' => $data['title']
        ]);
    }

    /**
     * @test
     */
    public function user_can_save_a_search_with_notification()
    {
        $data = [
            'title' => 'My Saved Search',
            'url' => $this->testUrl,
            'wants_notification' => 1,
            'frequency' => 'daily'
        ];

        $user = $this->createUser();

        $this->actingAs($user)
            ->json('POST', $this->endpoint, $data);

        $this->assertDatabaseHas('searches', [
            'user_id' => $user->id,
            'title' => $data['title'],
            'query' => parse_url($this->testUrl)['query'],
            'wants_notification' => 1,
            'frequency' => 'daily'
        ]);
    }

    /**
     * @test
     */
    public function user_can_save_a_search_without_notification()
    {
        $data = [
            'title' => 'My Saved Search',
            'url' => $this->testUrl
        ];

        $user = $this->createUser();

        $this->actingAs($user)
            ->json('POST', $this->endpoint, $data);

        $this->assertDatabaseHas('searches', [
            'user_id' => $user->id,
            'title' => $data['title'],
            'query' => parse_url($this->testUrl)['query'],
            'wants_notification' => 0,
            'frequency' => null,
            'searched_at' => null
        ]);
    }

    /**
     * @test
     */
    public function storing_a_search_returns_all_searches_for_user()
    {
        $data = [
            'title' => 'My Saved Search',
            'url' => $this->testUrl,
            'wants_notification' => 1,
            'frequency' => 'daily'
        ];

        $this->actingAs($this->createUser())
            ->json('POST', $this->endpoint, $data)
            ->assertJson([
                [
                    'title' => 'My Saved Search',
                    'wants_notification' => 1
                ]
            ]);
    }

    /**
     * @test
     */
    public function user_can_not_update_another_users_search()
    {
        $user = $this->createUser();

        $search = factory(Search::class)->create([
            'user_id' => $user
        ]);

        $this->actingAs($this->createUser())
            ->json('PUT', $this->endpoint . '/' . $search->id)
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_update_a_search()
    {
        $data = [
            'title' => 'My Saved Search',
            'url' => $this->testUrl,
            'wants_notification' => 1,
            'frequency' => 'daily'
        ];

        $user = $this->createUser();

        $search = factory(Search::class)->create([
            'user_id' => $user,
            'wants_notification' => 0,
            'frequency' => 'weekly'
        ]);

        $this->actingAs($user)
            ->json('PUT', $this->endpoint . '/' . $search->id, $data);

        $this->assertDatabaseHas('searches', [
            'user_id' => $user->id,
            'title' => $data['title'],
            'wants_notification' => 1,
            'frequency' => 'daily'
        ]);
    }

    /**
     * @test
     */
    public function user_can_toggle_notification_off()
    {
        $data = [
            'title' => 'My Saved Search'
        ];

        $user = $this->createUser();

        $search = factory(Search::class)->create([
            'user_id' => $user,
            'wants_notification' => 1
        ]);

        $this->actingAs($user)
            ->json('PUT', $this->endpoint . '/' . $search->id, $data);

        $this->assertDatabaseHas('searches', [
            'user_id' => $user->id,
            'title' => $data['title'],
            'wants_notification' => 0
        ]);
    }

    /**
     * @test
     */
    public function updating_a_search_returns_a_list_of_users_searches()
    {
        $data = [
            'title' => 'My Saved Search'
        ];

        $user = $this->createUser();

        $search = factory(Search::class)->create([
            'user_id' => $user
        ]);

        $this->actingAs($user)
            ->json('PUT', $this->endpoint . '/' . $search->id, $data)
            ->assertJson([
                [
                    'title' => 'My Saved Search'
                ]
            ]);
    }

    /**
     * @test
     */
    public function user_can_not_delete_another_users_search()
    {
        $user = $this->createUser();

        $search = factory(Search::class)->create([
            'user_id' => $user
        ]);

        $this->actingAs($this->createUser())
            ->json('DELETE', $this->endpoint . '/' . $search->id)
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_delete_a_search()
    {
        $user = $this->createUser();

        $search = factory(Search::class)->create([
            'user_id' => $user
        ]);

        $this->actingAs($user)
            ->json('DELETE', $this->endpoint . '/' . $search->id);

        $this->assertDatabaseMissing('searches', [
            'id' => $search->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * @test
     */
    public function destroying_a_search_returns_updated_list_of_saved_searched()
    {
        $user = $this->createUser();

        $search1 = factory(Search::class)->create([
            'user_id' => $user
        ]);
        $search2 = factory(Search::class)->create([
            'user_id' => $user
        ]);

        $this->actingAs($user)
            ->json('DELETE', $this->endpoint . '/' . $search2->id)
            ->assertJson([
                [
                    'id' => $search1->id
                ]
            ]);
    }
}
