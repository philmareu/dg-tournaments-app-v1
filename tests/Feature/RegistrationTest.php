<?php

namespace Tests\Feature;

use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use Illuminate\Auth\Events\Registered;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->seed('EmailNotificationTypesSeeder');
    }

    /**
     * @test
     */
    public function user_becomes_manager_of_any_tournaments_with_an_authorization_email_that_matches_their_email()
    {
        $user = factory(User::class)->create([
            'email' => 'email@email.com'
        ]);

        $tournament1 = factory(Tournament::class)->create([
            'authorization_email' => $user->email
        ]);

        $tournament2 = factory(Tournament::class)->create([
            'authorization_email' => $user->email
        ]);

        $tournament3 = factory(Tournament::class)->create([
            'authorization_email' => 'no@no.com'
        ]);

        event(new Registered($user));

        $this->assertDatabaseHas('managers', [
            'user_id' => $user->id,
            'tournament_id' => $tournament1->id
        ]);

        $this->assertDatabaseHas('managers', [
            'user_id' => $user->id,
            'tournament_id' => $tournament2->id
        ]);

        $this->assertDatabaseMissing('managers', [
            'user_id' => $user->id,
            'tournament_id' => $tournament3->id
        ]);
    }
}
