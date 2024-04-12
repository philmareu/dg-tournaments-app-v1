<?php

namespace DGTournaments\Listeners\Operations;

use DGTournaments\Events\TournamentCourseCreated;
use DGTournaments\Models\Tournament;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class CheckTournamentLatLng implements ShouldQueue
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
     * @param  TournamentCourseCreated  $event
     * @return void
     */
    public function handle(TournamentCourseCreated $event)
    {
        if($this->tournamentGeoNeedsUpdating($event->tournamentCourse->tournament))
        {
            DB::table('tournaments')
                ->where('id', $event->tournamentCourse->tournament->id)
                ->update([
                    'latitude' => $event->tournamentCourse->latitude,
                    'longitude' => $event->tournamentCourse->longitude
                ]);
        }
    }

    /**
     * @param TournamentCourseCreated $event
     * @return bool
     */
    private function tournamentGeoNeedsUpdating(Tournament $tournament) : bool
    {
        return ! $tournament->hasLatLng() ||
            ! $tournament->headquartersWasUpdated();
    }
}
