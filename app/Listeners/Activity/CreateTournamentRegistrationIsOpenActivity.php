<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\Registration\RegistrationIsOpen;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTournamentRegistrationIsOpenActivity
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
     * @param  RegistrationIsOpen  $event
     * @return void
     */
    public function handle(RegistrationIsOpen $event)
    {
        $activity = $this->createActivity('tournament.registration.is_open', $event->registration->tournament, null, $event->registration);

        $this->attachActivityToFeeds($event->registration->tournament->followers->load('user'), $activity);
    }
}
