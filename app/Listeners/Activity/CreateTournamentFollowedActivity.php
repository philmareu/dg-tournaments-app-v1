<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\TournamentFollowed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTournamentFollowedActivity implements ShouldQueue
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
     * @param  TournamentFollowed  $event
     * @return void
     */
    public function handle(TournamentFollowed $event)
    {
        $activity = $this->createActivity('tournament.followed', $event->follow->resource, $event->user);
    }
}
