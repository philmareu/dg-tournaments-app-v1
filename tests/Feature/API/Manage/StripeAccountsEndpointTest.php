<?php

namespace Tests\Feature\Endpoints\User;

use DGTournaments\Models\StripeAccount;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StripeAccountsEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected $endpoint = 'user/stripe';

    /**
     * @test
     */
    public function guests_can_not_remove_user_stripe_accounts()
    {
        $this->json('DELETE', $this->endpoint . '/' . factory(StripeAccount::class)->create()->id)
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function only_the_owner_can_remove_the_stripe_account()
    {
        $stripeAccount = factory(StripeAccount::class)->create();

        $this->actingAs($this->createUser())
            ->json('DELETE', $this->endpoint . '/' . $stripeAccount->id)
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function authenticated_user_can_remove_their_stripe_account()
    {
        $stripeAccount = factory(StripeAccount::class)->create();

        $this->actingAs($stripeAccount->user)
            ->json('DELETE', $this->endpoint . '/' . $stripeAccount->id);

        $this->assertDatabaseMissing('stripe_accounts', [
            'user_id' => $stripeAccount->user_id,
            'id' => $stripeAccount->id
        ]);
    }

    /**
     * @test
     */
    public function fresh_list_of_stripe_accounts_is_returned()
    {
        $user = $this->createUser();
        $stripeAccount1 = factory(StripeAccount::class)->create();
        $stripeAccount2 = factory(StripeAccount::class)->create();
        $stripeAccount3 = factory(StripeAccount::class)->create();

        $user->stripeAccounts()->save($stripeAccount1);
        $user->stripeAccounts()->save($stripeAccount2);

        $this->actingAs($user)
            ->json('DELETE', $this->endpoint . '/' . $stripeAccount1->id)
            ->assertJson([
                [
                    'id' => $stripeAccount2->id
                ]
            ])
            ->assertJsonMissing([
                [
                    'id' => $stripeAccount1->id
                ],
                [
                    'id' => $stripeAccount3->id
                ]
            ]);
    }
}
