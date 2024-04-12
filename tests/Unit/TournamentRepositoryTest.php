<?php

namespace Tests\Unit;

use DGTournaments\Models\Course;
use DGTournaments\Models\Tournament;
use DGTournaments\Services\DarkSky\DarkSkyApi;
use DGTournaments\Services\Foursquare\FoursquareApi;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TournamentRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function should_provide_a_list_of_courses_surrounding_a_given_tournament_model()
    {
        Event::fake();

        $defaultDistance = 2;

        $tournament = factory(Tournament::class)->create([
            'latitude' => 38.9717,
            'longitude' => -95.2353
        ]);

        $courses = factory(Course::class, 10)->create()->each(function(Course $course) use ($tournament, $defaultDistance) {
            $course->update([
                'latitude' => $tournament->latitude + rand(0, $defaultDistance * 99) / 100,
                'longitude' => $tournament->longitude + rand(0, $defaultDistance * 99) / 100
            ]);
        });

        $this->assertEquals(
            array_keys($courses->pluck('id')->toArray()),
            array_keys($this->getRepo()->getSurroundingCourses($tournament, $defaultDistance)->pluck('id')->toArray())
        );
    }

    private function getRepo()
    {
        return new \DGTournaments\Repositories\TournamentRepository(
            new Tournament(),
            new FoursquareApi(),
            new DarkSkyApi(),
            new Course()
        );
    }
}
