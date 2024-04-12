<?php

namespace Tests\Feature;

use DGTournaments\Events\TournamentClaimApproved;
use DGTournaments\Events\TournamentClaimRequestSubmitted;
use DGTournaments\Mail\Directors\ClaimRequest;
use DGTournaments\Mail\User\ClaimApprovedMailable;
use DGTournaments\Mail\User\ClaimSubmitted;
use DGTournaments\Models\User\User;
use DGTournaments\Notifications\TournamentClaimedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClaimingTournamentTest extends TestCase
{
    use DatabaseMigrations;

    protected $endpoint = 'tournament/claim';

    /** @test */
    public function user_can_submit_tournament_claim_request()
    {
        list($user, $tournament) = $this->submitClaimRequest();

        $claim = $tournament->claimRequest->first();

        $this->assertEquals($user->id, $claim->user_id);
    }

    /** @test */
    public function user_cannot_resubmit_claim_request()
    {
        list($user, $tournament) = $this->submitClaimRequest();

        $this->actingAs($user)
            ->json('POST', $this->endpoint . '/' . $tournament->id)
            ->assertStatus(403);
    }

    /** @test */
    public function user_cannot_submit_a_claim_for_a_tournament_they_already_have_access_to()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->attach($user);

        $this->actingAs($user)
            ->json('POST', $this->endpoint . '/' . $tournament->id)
            ->assertStatus(403);
    }

    /** @test */
    public function user_cannot_submit_a_claim_for_a_tournament_that_has_at_least_one_manager()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->attach(factory(User::class)->create());

        $this->actingAs($user)
            ->json('POST', $this->endpoint . '/' . $tournament->id)
            ->assertStatus(403);
    }

    /** @test */
    public function tournament_claim_request_submitted_fires_an_event()
    {
        Event::fake();

        list($user, $tournament) = $this->submitClaimRequest();
        $claimRequest = $tournament->claimRequest;

        Event::assertDispatched(TournamentClaimRequestSubmitted::class, function ($e) use ($claimRequest, $user, $tournament) {
            return $e->claimRequest->id === $claimRequest->id
                && $e->claimRequest->tournament->id === $tournament->id
                && $e->claimRequest->user->id === $user->id;
        });
    }

    /** @test */
//    public function tournament_claim_request_email_should_be_queued()
//    {
//
//    }

    /** @test */
    public function claim_request_email_is_sent_to_tournament_auth_email()
    {
        Mail::fake();

        list($user, $tournament) = $this->submitClaimRequest();
        $claimRequest = $tournament->claimRequest;

        Mail::assertQueued(ClaimRequest::class, function($mail) use ($claimRequest, $user, $tournament) {
            return $mail->tournament->claimRequest->id === $claimRequest->id
                && $mail->hasTo($tournament->authorization_email);
        });
    }

    /** @test */
//    public function tournament_claim_request_confirmation_email_should_be_queued()
//    {
//
//    }

    /** @test */
    public function claim_request_confirmation_email_is_sent_to_user()
    {
        Mail::fake();

        list($user, $tournament) = $this->submitClaimRequest();
        $claimRequest = $tournament->claimRequest;

        Mail::assertQueued(ClaimSubmitted::class, function($mail) use ($claimRequest, $user, $tournament) {
            return $mail->tournament->id === $claimRequest->tournament->id
                && $mail->hasTo($user->email);
        });
    }

    /** @test */
    public function approve_tournament_claim_request_page_loads()
    {
        list($user, $tournament) = $this->submitClaimRequest();
        $claimRequest = $tournament->claimRequest;

        $this->get($this->endpoint . '/confirm/' . $claimRequest->token)
            ->assertViewHas('claim')
            ->assertStatus(200);
    }

    /** @test */
    public function user_is_granted_access_to_tournament_after_approval()
    {
        list($user, $tournament) = $this->submitClaimRequest();
        $claimRequest = $tournament->claimRequest;

        $response = $this->json('POST', $this->endpoint . '/confirm/' . $claimRequest->token);
        $response->assertStatus(200);

        $this->assertEquals($user->id, $tournament->managers->first()->id);
    }

    /** @test */
    public function approved_claim_request_fires_event()
    {
        Event::fake();

        list($user, $tournament) = $this->submitClaimRequest();
        $claimRequest = $tournament->claimRequest;

        $this->json('POST', $this->endpoint . '/confirm/' . $claimRequest->token);

        Event::assertDispatched(TournamentClaimApproved::class, function ($e) use ($user, $tournament) {
            return $e->tournament->id === $tournament->id
                && $e->user->id === $user->id;
        });
    }

    /** @test */
//    public function approved_email_notification_is_queued()
//    {
//
//    }

    /** @test */
    public function slack_notification_is_sent_to_dgt_channel_about_an_approved_claim()
    {
        Notification::fake();

        list($user, $tournament) = $this->submitClaimRequest();
        $claimRequest = $tournament->claimRequest;

        $this->json('POST', $this->endpoint . '/confirm/' . $claimRequest->token);

        Notification::assertSentTo(
            $user,
            TournamentClaimedNotification::class,
            function ($notification, $channels) use ($user, $tournament) {
                return $notification->user->id === $user->id
                    && $notification->tournament->id === $tournament->id;
            }
        );
    }

    /** @test */
    public function approved_claim_email_is_sent_to_requesting_user()
    {
        Mail::fake();

        list($user, $tournament) = $this->submitClaimRequest();
        $claimRequest = $tournament->claimRequest;

        $this->json('POST', $this->endpoint . '/confirm/' . $claimRequest->token);

        Mail::assertQueued(ClaimApprovedMailable::class, function($mail) use ($claimRequest, $user, $tournament) {
            return $mail->tournament->id === $claimRequest->tournament->id
                && $mail->hasTo($claimRequest->user->email);
        });
    }

    /** @test */
    public function claim_request_is_deleted_after_approved()
    {
        list($user, $tournament) = $this->submitClaimRequest();
        $claimRequest = $tournament->claimRequest;

        $this->json('POST', $this->endpoint . '/confirm/' . $claimRequest->token);

        $this->assertNull($tournament->fresh()->claimRequest);
    }

    public function submitClaimRequest()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();

        $this->actingAs($user)
            ->json('POST', $this->endpoint . '/' . $tournament->id);

        return [$user, $tournament];
    }
}
