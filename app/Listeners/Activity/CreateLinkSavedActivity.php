<?php

namespace DGTournaments\Listeners\Activity;

use Carbon\Carbon;
use DGTournaments\Events\LinkSaved;
use DGTournaments\Models\Activity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateLinkSavedActivity implements ShouldQueue
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
     * @param  LinkSaved  $event
     * @return void
     */
    public function handle(LinkSaved $event)
    {
        if($this->noRecentActivity($event))
        {
            $activity = $this->createActivity('tournament.links.updated', $event->link->tournament, $event->user);
            $this->attachActivityToFeeds($event->link->tournament->followers, $activity);
        }
    }

    /**
     * @param LinkSaved $event
     * @return mixed
     */
    private function noRecentActivity(LinkSaved $event)
    {
        $activity = $this->activity
            ->where('type', 'tournament.links.updated')
            ->where('resource_id', $event->link->tournament->id)
            ->where('created_at', '>', Carbon::now()->subHours(6))
            ->count();

        return (bool) $activity == 0;
    }
}
