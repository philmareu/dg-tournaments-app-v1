<?php

namespace Tests\Feature\Tournament;

use DGTournaments\Events\TournamentSubmitted;
use DGTournaments\Mail\User\TournamentSubmittedConfirmationMailable;
use DGTournaments\Models\Classes;
use DGTournaments\Models\Format;
use DGTournaments\Models\SpecialEventType;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\Upload;
use DGTournaments\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\ValidationHelperTrait;

class SubmitTournamentTest extends TestCase
{
    use DatabaseMigrations, ValidationHelperTrait;

    protected $endpoint = 'manage/submit';
    
    /**
     * @test
     */
    public function the_submit_tournament_page_should_load()
    {
        $this->actingAs($this->createUser())
            ->call('GET', $this->endpoint)
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function guests_can_not_submit_a_tournament()
    {
        $this->json('POST', $this->endpoint)
            ->assertStatus(401);
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    /**
     * @test
     */
    public function submitting_a_tournament_requires_a_name()
    {
        $this->postValidationTest($this->endpoint, 'name');
    }

    /**
     * @test
     */
    public function submitting_a_tournament_requires_a_city()
    {
        $this->postValidationTest($this->endpoint, 'city');
    }

    /**
     * @test
     */
    public function submitting_a_tournament_requires_a_country()
    {
        $this->postValidationTest($this->endpoint, 'country');
    }

    /**
 * @test
 */
    public function submitting_a_tournament_requires_a_latitude()
    {
        $this->postValidationTest($this->endpoint, 'latitude');
    }

    /**
     * @test
     */
    public function submitting_a_tournament_requires_a_longitude()
    {
        $this->postValidationTest($this->endpoint, 'longitude');
    }

    /**
     * @test
     */
    public function submitting_a_tournament_requires_a_start()
    {
        $this->postValidationTest($this->endpoint, 'start');
    }

    /**
     * @test
     */
    public function submitting_a_tournament_requires_a_end()
    {
        $this->postValidationTest($this->endpoint, 'end');
    }

    /**
     * @test
     */
    public function submitting_a_tournament_requires_a_format_id()
    {
        $this->postValidationTest($this->endpoint, 'format_id');
    }

    /**
     * @test
     */
    public function submitting_a_tournament_requires_a_timezone()
    {
        $this->postValidationTest($this->endpoint, 'timezone');
    }

    /**
     * @test
     */
    public function submitting_a_tournament_requires_a_director()
    {
        $this->postValidationTest($this->endpoint, 'director');
    }

    /** @test */
    public function a_start_date_must_be_formatted_required()
    {
        $this->postValidationTest($this->endpoint, 'start', ['start' => '1997-1-1']);
    }

    /** @test */
    public function a_end_date_must_be_formatted_required()
    {
        $this->postValidationTest($this->endpoint, 'end', ['end' => '1997-1-1']);
    }

    /** @test */
    public function a_email_must_be_an_email()
    {
        $this->postValidationTest($this->endpoint, 'email', ['email' => 'Not an email']);
    }

    /** @test */
    public function a_format_must_exist_in_database()
    {
        $this->postValidationTest($this->endpoint, 'format_id', ['format_id' => 'x']);
    }

    /** @test */
    public function a_poster_id_must_exist_in_the_database()
    {
        $this->postValidationTest($this->endpoint, 'poster_id', ['poster_id' => 'x']);
    }

    /** @test */
    public function special_event_types_must_exist_in_database()
    {
        $this->postValidationTest($this->endpoint, 'format_id', ['special_event_type_ids' => [44, 54, 64]]);
    }

    /** @test */
    public function a_timezone_must_exist_in_php_timezone_list()
    {
        $this->postValidationTest($this->endpoint, 'timezone', ['timezone' => 'Not Valid Time Zone']);
    }

    /*
    |--------------------------------------------------------------------------
    | Submitting
    |--------------------------------------------------------------------------
    */

    /** @test */
//    public function user_can_submit_a_tournament_and_data_is_stored()
//    {
//        $data = $this->data();
//
//        $this->seed('ClassesTableSeeder');
//
//        $tournament = $this->getTournamentFromResponse(
//            $this->submitTournament($user = $this->createUser(), $data)
//        );
//
//        $basic = collect($data)->reject(function($value, $key) {
//            return in_array($key, ['start', 'end', 'special_event_type_ids']);
//        });
//
//        $special = collect($data)->filter(function($value, $key) {
//            return in_array($key, ['start', 'end', 'special_event_type_ids']);
//        });
//
//        $basic->each(function($value, $key) use ($tournament) {
//            $this->assertEquals($value, $tournament->$key);
//        });
//
//        $this->assertEquals($special['start'], $tournament->start->format('n-j-Y'));
//        $this->assertEquals($special['end'], $tournament->end->format('n-j-Y'));
//        $this->assertTrue(empty(array_diff($special['special_event_type_ids'], $tournament->specialEventTypes->pluck('id')->toArray())));
//
//        $this->assertEquals($user->email, $tournament->authorization_email);
//    }

    /** @test */
    public function user_is_added_as_manager_on_submitted_tournament()
    {
        $this->seed('ClassesTableSeeder');

        $tournament = $this->getTournamentFromResponse(
            $this->submitTournament($user = $this->createUser())
        );

        $this->assertTrue($tournament->managers->where('id', $user->id)->isNotEmpty());
    }

    /** @test */
    public function a_tournament_submitted_event_is_fired()
    {
        Event::fake();

        $tournament = $this->getTournamentFromResponse(
            $this->submitTournament($user = $this->createUser())
        );

        Event::assertDispatched(TournamentSubmitted::class, function($event) use ($tournament) {
            return $event->tournament->id === $tournament->id;
        });
    }

    /** @test */
    public function an_email_confirmation_is_sent_to_submitter()
    {
        Mail::fake();

        $tournament = $this->getTournamentFromResponse(
            $this->submitTournament($user = $this->createUser())
        );

        Mail::assertQueued(TournamentSubmittedConfirmationMailable::class, function ($mail) use ($tournament) {
            return $mail->tournament->id === $tournament->id
                && $mail->hasTo($tournament->managers->first()->email);
        });
    }

    /** @test */
    public function a_tournament_submitted_activity_is_created()
    {
        $tournament = $this->getTournamentFromResponse(
            $this->submitTournament($user = $this->createUser())
        );

        $this->assertDatabaseHas('activities', [
            'resource_type' => 'DGTournaments\Models\Tournament',
            'resource_id' => $tournament->id,
            'type' => 'tournament.submitted'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * @return TestResponse
     */
    public function submitTournament($user, $data = null) : TestResponse
    {
        return $this->actingAs($user)
            ->json('POST', $this->endpoint, is_null($data) ? $this->data() : $data);
    }

    public function getTournamentFromResponse(TestResponse $response)
    {
        $id = is_array($response->getOriginalContent()) ? $response->getOriginalContent()['id'] : $response->getOriginalContent()->id;

        return Tournament::find($id);
    }

    private function data()
    {
        $specialEventTypes = factory(SpecialEventType::class, 3)->create();
        $classes = factory(Classes::class, 2)->create();

        return [
            'name' => 'Test Tournament',
            'city' => 'Lawrence',
            'state_province' => 'Kansas',
            'country' => 'USA',
            'latitude' => '100.00',
            'longitude' => '-45.00',
            'start' => '1-1-2000',
            'end' => '1-1-2000',
            'director' => 'Director',
            'email' => 'email@email.com',
            'phone' => '785-555-5555',
            'description' => 'A disc golf tournament',
            'poster_id' => factory(Upload::class)->create()->id,
            'format_id' => factory(Format::class)->create()->id,
            'class_ids' => $classes->pluck('id'),
            'special_event_type_ids' => $specialEventTypes->pluck('id')->toArray(),
            'timezone' => 'America/Barbados',
            'accepted' => 1
        ];
    }
}
