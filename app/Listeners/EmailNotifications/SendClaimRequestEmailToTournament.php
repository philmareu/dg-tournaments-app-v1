<?php

namespace DGTournaments\Listeners\EmailNotifications;

use DGTournaments\Events\TournamentClaimRequestSubmitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendClaimRequestEmailToTournament implements ShouldQueue
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
     * @param  TournamentClaimRequestSubmitted  $event
     * @return void
     */
    public function handle(TournamentClaimRequestSubmitted $event)
    {
        Mail::to($event->claimRequest->tournament->authorization_email)
            ->send(new \DGTournaments\Mail\Directors\ClaimRequest($event->claimRequest->tournament));
    }
}
