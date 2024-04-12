<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\TournamentRegistrationUpdated;
use DGTournaments\Models\User\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTournamentRegistrationUpdatedActivity implements ShouldQueue
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
     * @param  TournamentRegistrationUpdated  $event
     * @return void
     */
    public function handle(TournamentRegistrationUpdated $event)
    {
        $activity = $this->createActivity('tournament.registration.updated', $event->registration->tournament, $event->user, $event->registration);

        $this->attachActivityToFeeds($event->registration->tournament->followers->load('user'), $activity);
    }
}
