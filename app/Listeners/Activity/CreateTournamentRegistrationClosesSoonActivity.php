<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\Registration\RegistrationClosesSoon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTournamentRegistrationClosesSoonActivity
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
     * @param  RegistrationClosesSoon  $event
     * @return void
     */
    public function handle(RegistrationClosesSoon $event)
    {
        $activity = $this->createActivity('tournament.registration.closes_soon', $event->registration->tournament, null, $event->registration);

        $this->attachActivityToFeeds($event->registration->tournament->followers->load('user'), $activity);
    }
}
