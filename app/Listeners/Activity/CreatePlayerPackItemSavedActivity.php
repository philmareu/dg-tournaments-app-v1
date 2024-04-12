<?php

namespace DGTournaments\Listeners\Activity;

use Carbon\Carbon;
use DGTournaments\Events\PlayerPackItemSaved;
use DGTournaments\Models\Activity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatePlayerPackItemSavedActivity implements ShouldQueue
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
     * @param  PlayerPackItemSaved  $event
     * @return void
     */
    public function handle(PlayerPackItemSaved $event)
    {
        if($this->noRecentActivity($event))
        {
            $activity = $this->createActivity('tournament.player_pack.updated', $event->playerPackItem->playerPack->tournament, $event->user);
            $this->attachActivityToFeeds($event->playerPackItem->playerPack->tournament->followers, $activity);
        }
    }

    /**
     * @param PlayerPackItemSaved $event
     * @return mixed
     */
    private function noRecentActivity(PlayerPackItemSaved $event)
    {
        $activity = $this->activity
            ->where('type', 'tournament.player_pack.updated')
            ->where('resource_id', $event->playerPackItem->playerPack->tournament->id)
            ->where('created_at', '>', Carbon::now()->subHours(6))
            ->count();

        return (bool) $activity == 0;
    }
}
