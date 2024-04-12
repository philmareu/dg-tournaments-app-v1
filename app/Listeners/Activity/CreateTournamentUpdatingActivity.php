<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\Models\TournamentUpdating;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreateTournamentUpdatingActivity implements ShouldQueue
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
     * @param  TournamentUpdating  $event
     * @return void
     */
    public function handle(TournamentUpdating $event)
    {
        Log::info($event->changes);

        // Poster
        if(isset($event->changes['poster_id']))
        {
            $activity = $this->createActivity('tournament.poster.updated', $event->tournament, $event->user, $event->tournament->poster);
            $this->addToFeeds($event, $activity);
        }

        // Headquarters
        if(isset($event->changes['latitude']) || isset($event->changes['longitude']))
        {
            $activity = $this->createActivity('tournament.headquarters.updated', $event->tournament, $event->user);
            $this->addToFeeds($event, $activity);
        }

        // Name
        if(isset($event->changes['name']))
        {
            $data = [
                'old' => $event->original['name'],
                'new' => $event->tournament->name
            ];

            $activity = $this->createActivity('tournament.name.updated', $event->tournament, $event->user, collect($data));
            $this->addToFeeds($event, $activity);
        }

        // Dates
        if(isset($event->changes['start']) || isset($event->changes['end']))
        {
            $data = [
                'old' => [
                    'start' => $event->original['start'],
                    'end' => $event->original['end']
                ]
            ];

            $activity = $this->createActivity('tournament.date.updated', $event->tournament, $event->user, collect($data));
            $this->addToFeeds($event, $activity);
        }

        // Description
        if(isset($event->changes['description']))
        {
            $activity = $this->createActivity('tournament.description.updated', $event->tournament, $event->user, collect([
                'old' => $event->original['description']
            ]));
            $this->addToFeeds($event, $activity);
        }
    }

    /**
     * @param TournamentUpdating $event
     * @param $activity
     */
    private function addToFeeds(TournamentUpdating $event, $activity)
    {
        $this->attachActivityToFeeds($event->tournament->followers, $activity);
    }
}
