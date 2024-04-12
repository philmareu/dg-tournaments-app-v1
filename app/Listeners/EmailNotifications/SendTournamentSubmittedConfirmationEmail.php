<?php

namespace DGTournaments\Listeners\EmailNotifications;

use DGTournaments\Events\TournamentSubmitted;
use DGTournaments\Mail\User\TournamentSubmittedConfirmationMailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendTournamentSubmittedConfirmationEmail
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
     * @param  TournamentSubmitted  $event
     * @return void
     */
    public function handle(TournamentSubmitted $event)
    {
        Mail::to($event->tournament->managers->first())
            ->send(new TournamentSubmittedConfirmationMailable($event->tournament));
    }
}
