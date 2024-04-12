<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\Models\TournamentCreated;
use DGTournaments\Models\FlagType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetDefaultFlags implements ShouldQueue
{
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
     * @param  TournamentCreated  $event
     * @return void
     */
    public function handle(TournamentCreated $event)
    {
        if(! $event->tournament->hasLatLng()) $event->tournament->flags()->attach(1, ['notes' => 'Created with no lat and lng']);

        $event->tournament->flags()->attach(2, [
            'notes' => 'Created with no course(s)',
            'review_on' => $event->tournament->start->subMonths(3)
        ]);

        $event->tournament->flags()->attach(3, [
            'notes' => 'Created with no registration',
            'review_on' => $event->tournament->start->subMonths(3)
        ]);
    }
}
