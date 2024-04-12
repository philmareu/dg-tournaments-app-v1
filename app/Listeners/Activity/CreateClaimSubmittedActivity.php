<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\TournamentClaimRequestSubmitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateClaimSubmittedActivity implements ShouldQueue
{
    use SavesActivities;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TournamentClaimRequestSubmitted  $event
     * @return void
     */
    public function handle(TournamentClaimRequestSubmitted $event)
    {
        $this->createActivity('tournament.claim.submitted', $event->claimRequest->tournament, $event->claimRequest->user);
    }
}
