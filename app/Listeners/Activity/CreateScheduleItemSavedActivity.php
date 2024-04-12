<?php

namespace DGTournaments\Listeners\Activity;

use Carbon\Carbon;
use DGTournaments\Events\ScheduleSaved;
use DGTournaments\Models\Activity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreateScheduleItemSavedActivity implements ShouldQueue
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
     * @param  ScheduleSaved  $event
     * @return void
     */
    public function handle(ScheduleSaved $event)
    {
        if($this->noRecentActivity($event))
        {
            $activity = $this->createActivity('tournament.schedule.updated', $event->schedule->tournament, $event->user);
            $this->attachActivityToFeeds($event->schedule->tournament->followers, $activity);
        }
    }

    /**
     * @param ScheduleSaved $event
     * @return mixed
     */
    private function noRecentActivity(ScheduleSaved $event)
    {
        $activity = $this->activity
            ->where('type', 'tournament.schedule.updated')
            ->where('resource_id', $event->schedule->tournament->id)
            ->where('created_at', '>', Carbon::now()->subHours(6))
            ->count();

        return (bool) $activity == 0;
    }
}
