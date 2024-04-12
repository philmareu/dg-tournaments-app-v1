<?php

namespace DGTournaments\Listeners\Activity;

use Carbon\Carbon;
use DGTournaments\Events\MediaSaved;
use DGTournaments\Models\Activity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateMediaSavedActivity implements ShouldQueue
{
    use SavesActivities;

    protected $activity;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Handle the event.
     *
     * @param  MediaSaved  $event
     * @return void
     */
    public function handle(MediaSaved $event)
    {
        if($this->noRecentActivity($event))
        {
            $activity = $this->createActivity('tournament.media.updated', $event->tournament, $event->user);
            $this->attachActivityToFeeds($event->tournament->followers, $activity);
        }
    }

    /**
     * @param MediaSaved $event
     * @return mixed
     */
    private function noRecentActivity(MediaSaved $event)
    {
        $activity = $this->activity
            ->where('type', 'tournament.media.updated')
            ->where('resource_id', $event->tournament->id)
            ->where('created_at', '>', Carbon::now()->subHours(6))
            ->count();

        return (bool) $activity == 0;
    }
}
