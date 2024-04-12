<?php

namespace Tests\Feature\Endpoints\Tournament;

use Carbon\Carbon;
use DGTournaments\Events\TournamentRegistrationUpdated;
use DGTournaments\Models\Registration;
use DGTournaments\Models\Tournament;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegistrationEndpointTest extends TestCase
{
    use DatabaseMigrations;

    protected $endpoint = 'tournament/registration/';

    /*
    |--------------------------------------------------------------------------
    | POST
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function guests_cannot_store_tournament_registration()
    {
        $this->checkGuestAccess('POST', 'tournament/registration/' . factory(Tournament::class)->create()->id);
    }

    /** @test */
    public function only_managers_can_store_tournament_registration()
    {
        $this->checkManagerAccess('POST', 'tournament/registration/' . factory(Tournament::class)->create()->id);
    }

    /** @test */
    public function storing_tournament_registration_requires_open_date_field()
    {
        $this->storing('opens_at');
    }

    /** @test */
    public function storing_tournament_registration_open_date_from_be_of_valid_format()
    {
        $this->storing('opens_at', ['opens_at' => '1234-1234-1234']);
    }

    /** @test */
    public function storing_tournament_registration_closes_date_from_be_of_valid_format()
    {
        $this->storing('closes_at', ['closes_at' => '1234-1234-1234']);
    }

    /** @test */
    public function storing_tournament_registration_url_must_be_valid()
    {
        $this->storing('url', ['url' => 'Not valid URL']);
    }

    /** @test */
    public function manager_can_store_tournament_registration()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $data = [
            'opens_at' => '1-1-2000',
            'closes_at' => '1-30-2000',
            'url' => 'http://testing.com'
        ];

        $this->actingAs($user)
            ->json('POST', $this->endpoint . $tournament->id, $data)
            ->assertJson([
                'id' => 1,
                'tournament_id' => 1,
                'url' => 'http://testing.com'
            ]);

        $this->assertInstanceOf(Carbon::class, $tournament->load('registration')->registration->opens_at);
        $this->assertInstanceOf(Carbon::class, $tournament->load('registration')->registration->closes_at);
        $this->assertEquals($data['url'], $tournament->load('registration')->registration->url);
    }

    /** @test */
    public function storing_tournament_registration_url_should_set_to_null_if_none_submitted()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $data = [
            'opens_at' => '1-1-2000',
            'closes_at' => '1-30-2000'
        ];

        $this->actingAs($user)
            ->json('POST', $this->endpoint . $tournament->id, $data)
            ->assertJson([
                'url' => null
            ]);

        $this->assertNull($tournament->load('registration')->registration->url);
    }

    /*
    |--------------------------------------------------------------------------
    | PUT
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function guests_cannot_update_tournament_registration()
    {
        $this->checkGuestAccess('PUT', 'tournament/registration/' . factory(Registration::class)->create()->id);
    }

    /** @test */
    public function only_managers_can_update_tournament_registration()
    {
        $this->checkManagerAccess('PUT', 'tournament/registration/' . factory(Registration::class)->create()->id);
    }

    /** @test */
    public function updating_tournament_registration_open_date_from_be_of_valid_format()
    {
        $this->updating('opens_at', ['opens_at' => '1234-1234-1234']);
    }

    /** @test */
    public function updating_tournament_registration_closes_date_from_be_of_valid_format()
    {
        $this->updating('closes_at', ['closes_at' => '1234-1234-1234']);
    }

    /** @test */
    public function updating_tournament_registration_url_must_be_valid()
    {
        $this->updating('url', ['url' => 'Not valid URL']);
    }

    /** @test */
    public function managers_can_update_tournament_registration()
    {
        $data = [
            'opens_at' => '1-1-2000',
            'closes_at' => '1-30-2000',
            'url' => 'http://testing.com'
        ];

        list($user, $tournament) = $this->createTournamentWithManager();

        $registration = $tournament->registration()->create(factory(Registration::class)->make()->toArray());

        $this->actingAs($user)
            ->json('PUT', 'tournament/registration/' . $registration->id, $data)
            ->assertJson([
                'url' => 'http://testing.com'
            ]);

        $registration->fresh();

        $this->assertEquals($data['opens_at'], $tournament->registration->opens_at->format('n-j-Y'));
        $this->assertEquals($data['closes_at'], $tournament->registration->closes_at->format('n-j-Y'));
        $this->assertEquals($data['url'], $tournament->registration->url);
    }

    /** @test */
    public function updating_tournament_registration_url_should_set_to_null_if_none_submitted()
    {
        $data = [
            'opens_at' => '1-1-2000',
            'closes_at' => '1-30-2000'
        ];

        list($user, $tournament) = $this->createTournamentWithManager();

        $registration = $tournament->registration()->create(factory(Registration::class)->make()->toArray());

        $this->actingAs($user)
            ->json('PUT', 'tournament/registration/' . $registration->id, $data)
            ->assertJson([
                'url' => null
            ]);

        $registration->fresh();

        $this->assertNull($tournament->registration->url);
    }

    /** @test */
    public function registration_event_is_fired_with_registration_is_stored()
    {
        Event::fake();
        $data = [
            'opens_at' => '1-1-2000',
            'closes_at' => '1-30-2000',
            'url' => 'http://testing.com'
        ];

        list($user, $tournament) = $this->createTournamentWithManager();

        $this->actingAs($user)
            ->json('POST', 'tournament/registration/' . $tournament->id, $data);

        $registration = $tournament->registration;

        Event::assertDispatched(TournamentRegistrationUpdated::class, function($event) use ($registration, $user) {
            return $event->registration->id === $registration->id
                && $event->user->id === $user->id
                && $event->registration->tournament;
        });
    }

    /** @test */
    public function a_registration_updated_activity_is_created_when_the_registration_is_stored()
    {
        $data = [
            'opens_at' => '1-1-2000',
            'closes_at' => '1-30-2000',
            'url' => 'http://testing.com'
        ];

        list($user, $tournament) = $this->createTournamentWithManager();

        $this->actingAs($user)
            ->json('POST', 'tournament/registration/' . $tournament->id, $data);

        $activity = $tournament->activities->first();

        $this->assertEquals('DGTournaments\Models\Tournament', $activity->resource_type);
        $this->assertEquals($tournament->id, $activity->resource_id);
        $this->assertEquals('tournament.registration.updated', $activity->type);
        $this->assertEquals($tournament->registration->opens_at, $activity->data->opens_at);
    }

    private function storing($key, $data = [])
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $this->actingAs($user)
            ->call('POST', 'tournament/registration/' . $tournament->id, $data)
            ->assertSessionHasErrors($key);
    }

    private function updating($key, $data = [])
    {
        list($user, $tournament) = $this->createTournamentWithManager();
        $tournament->registration()->save(factory(Registration::class)->make());

        $this->actingAs($user)
            ->call('PUT', 'tournament/registration/' . $tournament->registration->id, $data)
            ->assertSessionHasErrors($key);
    }
}
