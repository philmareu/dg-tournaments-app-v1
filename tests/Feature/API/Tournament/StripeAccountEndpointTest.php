<?php

namespace Tests\Feature\Endpoints\Tournament;

use DGTournaments\Models\StripeAccount;
use DGTournaments\Models\Tournament;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StripeAccountEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function guests_cannot_update_tournament_stripe_account()
    {

        $this->json('DELETE', 'tournament/stripe/' . factory(Tournament::class)->create()->id)
            ->assertStatus(401);
    }

    /** @test */
    public function only_manager_can_update_tournament_stripe_account()
    {

        $this->actingAs($this->createUser())
            ->json('DELETE', 'tournament/stripe/' . factory(Tournament::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function manager_can_update_tournament_stripe_account()
    {
        list($user, $tournament) = $this->createTournamentWithManager();
        $user->stripeAccounts()->save(factory(StripeAccount::class)->make());

        $this->actingAs($user)
            ->json('PUT', 'tournament/stripe/' . $tournament->id, ['stripe_account_id' => 1])
            ->assertJson([
                'id' => 1
            ]);
    }

    /** @test */
    public function manager_can_remove_tournament_stripe_account()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $this->actingAs($user)
            ->json('DELETE', 'tournament/stripe/' . $tournament->id);

        $this->assertNull($tournament->stripeAccount);
    }

    /** @test */
    public function updating_tournament_stripe_account_requires_a_stripe_account_id()
    {
        $this->updating()->assertSessionHasErrors('stripe_account_id');
    }

    /** @test */
    public function user_cannot_use_a_stripe_id_they_do_not_own()
    {
        $this->updating(['user_stripe_account_id' => 10])->assertSessionHasErrors('stripe_account_id');
    }

    public function updating($data = [])
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        return $this->actingAs($user)
            ->call('PUT', 'tournament/stripe/' . $tournament->id, $data);
    }
}
