<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\StripeAccount;
use DGTournaments\Models\User\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StripeConnectTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function only_a_manager_can_update_stripe_account()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();

        $response = $this->actingAs($user)
            ->json('PUT', '/tournament/stripe/' . $tournament->id, [
                'stripe_account_id' => 1
            ])
            ->assertStatus(403);
    }

    /** @test */
    public function only_a_manager_can_destroy_stripe_account()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();

        $response = $this->actingAs($user)
            ->json('DELETE', '/tournament/stripe/' . $tournament->id)
            ->assertStatus(403);
    }

    /** @test */
    public function updating_a_tournament_stripe_account_requires_stripe_id()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $response = $this->actingAs($user)
            ->json('PUT', '/tournament/stripe/' . $tournament->id)
            ->assertStatus(422);

        $this->assertArrayHasKey('stripe_account_id', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function manager_can_update_a_selected_stripe_account_for_a_tournament()
    {

        $user = $this->createUser();
        $userStripeAccount = $user->stripeAccounts()->save(factory(StripeAccount::class)->make());
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $response = $this->actingAs($user)
            ->json('PUT', '/tournament/stripe/' . $tournament->id, [
                'stripe_account_id' => $userStripeAccount->id
            ])
            ->assertStatus(200)
            ->assertJson($tournament->fresh()->stripeAccount->toArray());

        $this->assertEquals($userStripeAccount->id, $tournament->fresh()->stripe_account_id);
    }

    /** @test */
    public function the_stripe_account_must_be_owned_by_the_manager_who_selects_it()
    {

        $user = $this->createUser();
        $userStripeAccount = $user->stripeAccounts()->save(factory(StripeAccount::class)->make());
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $userStripeAccount = factory(StripeAccount::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/tournament/stripe/' . $tournament->id, [
                'stripe_account_id' => $userStripeAccount->id
            ])
            ->assertStatus(422);

        $this->assertArrayHasKey('stripe_account_id', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function manager_can_remove_stripe_account_from_tournament()
    {

        $user = $this->createUser();
        $userStripeAccount = $user->stripeAccounts()->save(factory(StripeAccount::class)->make());
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $response = $this->actingAs($user)
            ->json('DELETE', '/tournament/stripe/' . $tournament->id)
            ->assertStatus(200);

        $this->assertNull($tournament->fresh()->stripe_account_id);
    }
}
