<?php

namespace DGTournaments\Listeners\Notifications\Admin;

use DGTournaments\Events\TournamentClaimApproved;
use DGTournaments\Models\User\User;
use DGTournaments\Notifications\TournamentClaimedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTournamentClaimedNotification
{
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
        User::find(1)->notify(new TournamentClaimedNotification($event->user, $event->tournament));
    }
}
