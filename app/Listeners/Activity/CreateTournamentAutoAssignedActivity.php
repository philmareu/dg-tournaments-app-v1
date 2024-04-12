<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\TournamentAutoAssigned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTournamentAutoAssignedActivity
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
     * @param  TournamentAutoAssigned  $event
     * @return void
     */
    public function handle(TournamentAutoAssigned $event)
    {
        $activity = $this->createActivity('tournament.user.assigned', $event->tournament, null, $event->user);

        $this->attachActivityToFeeds($event->user, $activity);
    }
}
