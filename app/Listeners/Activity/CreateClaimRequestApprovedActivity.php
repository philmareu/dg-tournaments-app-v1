<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\TournamentClaimApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateClaimRequestApprovedActivity implements ShouldQueue
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
     * @param  TournamentClaimApproved  $event
     * @return void
     */
    public function handle(TournamentClaimApproved $event)
    {
        $this->createActivity('tournament.claim.approved', $event->tournament);
    }
}
