<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\Models\SponsorshipCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSponsorshipCreatedActivity implements ShouldQueue
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
     * @param  SponsorshipCreated  $event
     * @return void
     */
    public function handle(SponsorshipCreated $event)
    {
        $activity = $this->createActivity('tournament.sponsorship.created', $event->sponsorship->tournament, $event->user, $event->sponsorship);

        $this->attachActivityToFeeds($event->sponsorship->tournament->followers, $activity);
    }
}
