<?php

namespace Tests\Feature\Endpoints\Tournament;

use DGTournaments\Models\Course;
use DGTournaments\Models\Tournament;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SurroundingCoursesEndpointTest extends TestCase
{
    use DatabaseMigrations;

    protected $endpoint = 'tournament/surrounding-courses/';

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function guests_can_not_retrieve_list_of_surrounding_courses()
    {

        $this->json('GET', $this->endpoint . factory(Tournament::class)->create()->id)
            ->assertStatus(401);
    }

    /** @test */
    public function should_return_a_list_of_courses_within_a_set_range_of_given_tournament()
    {
        $defaultDistance = 2;

        $tournament = factory(Tournament::class)->create();

        $courses = factory(Course::class, 3)->create()->each(function(Course $course) use ($tournament, $defaultDistance) {
            $course->update([
                'latitude' => $tournament->latitude + rand(0, $defaultDistance * 99) / 100,
                'longitude' => $tournament->longitude + rand(0, $defaultDistance * 99) / 100
            ]);
        });

        $this->actingAs($this->createUser())
            ->json('GET', $this->endpoint . $tournament->id)
            ->assertStatus(200)
            ->assertJson($courses->only('name')->toArray());
    }
}
