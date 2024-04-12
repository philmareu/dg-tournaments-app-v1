<?php

namespace Tests\Feature\Enpoints;

use DGTournaments\Data\Dates;
use DGTournaments\Data\Location;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\StripeAccount;
use DGTournaments\Models\TournamentSponsor;
use DGTournaments\Models\Upload;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TournamentDataEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function loads_all_tournament_information_needed_for_the_show_page()
    {
        $tournament = $this->createTournament();
        $upload = factory(Upload::class)->create();
        $tournament->media()->save($upload);

        $sponsorship = factory(Sponsorship::class)->create();
        $sponsorship->tournamentSponsors()->save(factory(TournamentSponsor::class)->create());
        $tournament->sponsorships()->save($sponsorship);

        $this->json('GET', 'tournament/' . $tournament->id)
            ->assertJson([
                'id' => $tournament->id,
                'name' => $tournament->name,
                'city' => $tournament->city,
                'state_province' => $tournament->state_province,
                'country' => $tournament->country,
                'dates' => Dates::make($tournament->start, $tournament->end)->formattedDateSpan(),
                'location' => Location::make($tournament->city, $tournament->country, $tournament->state_province)->formatted(),
                'latitude' => $tournament->latitude,
                'longitude' => $tournament->longitude,
                'start' => $tournament->start->format('Y-m-d'),
                'end' => $tournament->end->format('Y-m-d'),
                'email' => $tournament->email,
                'phone' => $tournament->phone,
                'description' => $tournament->description,
                'format' => $tournament->format->toArray(),
                'poster' => $tournament->poster->toArray(),
                'managers' => $tournament->managers->toArray(),
                'special_event_types' => $tournament->specialEventTypes->toArray(),
                'special_event_type_ids' => $tournament->specialEventTypes->pluck('id')->toArray(),
                'registration' => $tournament->registration->toArray(),
                'pdga_tiers' => $tournament->pdgaTiers->toArray(),
                'path' => $tournament->path,
                'courses' => $tournament->courses->toArray(),
                'schedule' => $tournament->schedule->toArray(),
                'schedule_by_day' => $tournament->schedule->groupedByDay()->toArray(),
                'player_packs' => $tournament->playerPacks->toArray(),
                'links' => $tournament->links->toArray(),
                'media' => $tournament->media->toArray(),
                'can_except_online_payments' => $tournament->canExceptOnlinePayments(),
                'claim_request' => $tournament->claimRequest,
//                'sponsorships' => $tournament->load('sponsorships.tournamentSponsors.sponsor.logo')->sponsorships
            ]);
    }

    /**
     * @test
     */
    public function stripe_account_does_not_load_for_guest()
    {
        $tournament = $this->createTournament();
        $tournament->stripeAccount()->associate(factory(StripeAccount::class)->create())->save();

        $this->json('GET', 'tournament/' . $tournament->id)
            ->assertJsonMissing([
                'stripe_account' => $tournament->stripeAccount->toArray(),
                'stripe_account_id' => $tournament->stripe_account_id
            ]);
    }

    /**
     * @test
     */
    public function stripe_account_does_show_up_for_managers_of_the_tournament()
    {
        list($user, $tournament) = $this->createTournamentWithManager();
        $stripeAccount = factory(StripeAccount::class)->create();
        $user->stripeAccounts()->save($stripeAccount);
        $tournament->stripeAccount()->associate($stripeAccount)->save();

        $this->actingAs($user)
            ->json('GET', 'tournament/' . $tournament->id)
            ->assertJson([
                'stripe_account' => $tournament->stripeAccount->toArray(),
                'stripe_account_id' => $tournament->stripe_account_id
            ]);
    }
}
