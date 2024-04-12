<?php

namespace DGTournaments\Listeners\EmailNotifications;

use DGTournaments\Events\TournamentClaimRequestSubmitted;
use DGTournaments\Mail\User\ClaimSubmitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendClaimRequestConfirmationEmail implements ShouldQueue
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
        Mail::to($event->claimRequest->user->email)
            ->send(new ClaimSubmitted($event->claimRequest->tournament));
    }
}
