<?php

namespace DGTournaments\Listeners\Activity;

use DGTournaments\Events\CourseAddedToTournament;
use DGTournaments\Events\TournamentCourseCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTournamentCourseCreatedActivity implements ShouldQueue
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
     * @param  TournamentCourseCreated  $event
     * @return void
     */
    public function handle(TournamentCourseCreated $event)
    {
        $activity = $this->createActivity(
            'tournament.course.created',
            $event->tournamentCourse->tournament,
            $event->user,
            $event->tournamentCourse
        );

        $this->attachActivityToFeeds($event->tournamentCourse->tournament->followers, $activity);

        $this->createActivity(
            'tournament_course.created',
            $event->tournamentCourse,
            $event->user,
            $event->tournamentCourse
        );
    }
}
