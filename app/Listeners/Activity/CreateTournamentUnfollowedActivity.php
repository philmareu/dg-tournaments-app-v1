<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\TournamentUnfollowed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreateTournamentUnfollowedActivity implements ShouldQueue
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
     * @param  TournamentUnfollowed  $event
     * @return void
     */
    public function handle(TournamentUnfollowed $event)
    {
        $this->createActivity('tournament.unfollowed', $event->tournament, $event->user);
    }
}
