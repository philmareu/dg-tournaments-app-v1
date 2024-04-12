<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\RatingUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatePdgaRatingUpdatedActivity
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
     * @param  RatingUpdatedEvent  $event
     * @return void
     */
    public function handle(RatingUpdatedEvent $event)
    {
        $activity = $this->createActivity('pdga.rating.updated', $event->user, null, collect(['old' => $event->old, 'new' => $event->new]));

        $event->user->feed()->save($activity);
    }
}
