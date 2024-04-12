<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\TournamentSubmitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTournamentSubmittedActivity
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
     * @param  TournamentSubmitted  $event
     * @return void
     */
    public function handle(TournamentSubmitted $event)
    {
        $this->createActivity('tournament.submitted', $event->tournament, $event->user);
    }
}
