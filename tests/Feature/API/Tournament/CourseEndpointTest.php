<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Events\CourseAddedToTournament;
use DGTournaments\Events\CourseCreated;
use DGTournaments\Events\TournamentCourseCreated;
use DGTournaments\Models\Activity;
use DGTournaments\Models\Course;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\TournamentCourse;
use DGTournaments\Models\TournamentCourseHole;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CourseEndpointTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_new_course_is_created_if_course_id_is_not_provided()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray())
            ->assertJson($tournament->courses->toArray());

        $savedCourse = Course::find(1);

        $this->assertNotNull($savedCourse);
        $this->assertEquals($course->name, $savedCourse->name);
    }

    /** @test */
    public function a_tournament_course_is_created_and_references_parent_course_and_tournament()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make([
            'holes' => 18
        ]);

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $savedCourse = Course::find(1);
        $tournamentCourse = TournamentCourse::find(1);

        $this->assertEquals($tournamentCourse->course_id, $savedCourse->id);
        $this->assertEquals($tournament->courses->first()->id, $tournamentCourse->id);
    }

    /** @test */
    public function only_managers_can_add_courses_to_tournaments()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();

        $course = factory(Course::class)->make([
            'holes' => 18
        ]);

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function only_managers_can_update_tournament_course_information()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournamentCourse = factory(TournamentCourse::class)->create([
            'tournament_id' => $tournament->id
        ]);

        $this->actingAs($user)
            ->json('PUT', 'tournament/courses/' . $tournamentCourse->id)
            ->assertStatus(403);
    }

    /** @test */
    public function managers_can_edit_tournament_course_attributes()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $tournamentCourse = factory(TournamentCourse::class)->create([
            'tournament_id' => $tournament->id
        ]);

        $data = [
            'name' => 'New Name',
            'holes' => 1,
            'latitude' => 1.0,
            'longitude' => -2.0,
            'address' => 'New Address 1',
            'address_2' => 'New Address 2',
            'city' => 'New City',
            'state_province' => 'New State',
            'country' => 'New Country',
            'notes' => 'New Notes',
            'directions' => 'New Directions'
        ];

        $this->actingAs($user)
            ->json('PUT', 'tournament/courses/' . $tournamentCourse->id, $data)
            ->assertJson($data);

        $tournamentCourse = $tournamentCourse->fresh();

        $this->assertEquals($data['name'], $tournamentCourse->name);
        $this->assertEquals($data['holes'], $tournamentCourse->holes);
        $this->assertEquals($data['latitude'], $tournamentCourse->latitude);
        $this->assertEquals($data['longitude'], $tournamentCourse->longitude);
        $this->assertEquals($data['address'], $tournamentCourse->address);
        $this->assertEquals($data['address_2'], $tournamentCourse->address_2);
        $this->assertEquals($data['city'], $tournamentCourse->city);
        $this->assertEquals($data['state_province'], $tournamentCourse->state_province);
        $this->assertEquals($data['country'], $tournamentCourse->country);
        $this->assertEquals($data['notes'], $tournamentCourse->notes);
        $this->assertEquals($data['directions'], $tournamentCourse->directions);
    }

    /** @test */
    public function tournament_geo_is_set_to_tournament_course_location_if_null()
    {
        $user = $this->createUser();
        $tournament = factory(Tournament::class)->states('no-geo')->create();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $this->assertDatabaseHas('tournaments', [
            'id' => $tournament->id,
            'latitude' => $course->latitude,
            'longitude' => $course->longitude
        ]);
    }

    /** @test */
    public function no_activity_is_created_when_tournament_geo_is_set_to_tournament_course_location_if_null()
    {
        $user = $this->createUser();
        $tournament = factory(Tournament::class)->states('no-geo')->create();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $this->assertDatabaseMissing('activities', [
            'resource_type' => Tournament::class,
            'resource_id' => $tournament->id,
            'type' => 'tournament.headquarters.updated'
        ]);
    }

    /** @test */
    public function tournament_geo_is_set_to_tournament_course_location_if_headquarters_has_not_been_updated()
    {
        $user = $this->createUser();
        $tournament = factory(Tournament::class)->create();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $this->assertDatabaseHas('tournaments', [
            'id' => $tournament->id,
            'latitude' => $course->latitude,
            'longitude' => $course->longitude
        ]);
    }

    /** @test */
    public function new_course_created_event_is_dispatched()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        Event::fake();

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $course = Course::find(1);

        Event::assertDispatched(CourseCreated::class, function($event) use ($course, $user) {
            return $event->course->id === $course->id
                && $event->user->id === $user->id;
        });
    }

    /** @test */
    public function new_course_created_activity_is_created()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $activity = Activity::where('resource_type', 'DGTournaments\Models\Course')
            ->where('resource_id', 1)
            ->where('type', 'course.created')
            ->first();

        $this->assertNotNull($activity);
        $this->assertEquals($course->name, $activity->data->name);
    }

    /** @test */
    public function course_added_to_tournament_event_is_dispatched()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        Event::fake();

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $tournamentCourse = TournamentCourse::find(1);

        Event::assertDispatched(TournamentCourseCreated::class, function($event) use ($tournamentCourse, $user) {
            return $event->tournamentCourse->id === $tournamentCourse->id
                && $event->user->id === $user->id;
        });
    }

    /** @test */
    public function course_added_to_tournament_activity_is_created()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $activity = Activity::where('resource_type', 'DGTournaments\Models\Tournament')
            ->where('resource_id', 1)
            ->where('type', 'tournament.course.created')
            ->first();

        $this->assertNotNull($activity);
        $this->assertEquals($tournament->name, $activity->data->tournament->name);
    }

    /** @test */
    public function only_a_manager_can_delete_a_tournament_course()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $this->actingAs($this->createUser())
            ->json('DELETE', '/tournament/courses/' . $tournament->courses->first()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function manager_can_delete_tournament_course()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make(
            ['holes' => 18]
        );

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $this->actingAs($user)
            ->json('DELETE', '/tournament/courses/' . $tournament->courses->first()->id)
            ->assertJson([]);

        $this->assertEquals(0, $tournament->fresh()->courses->count());
    }

    /** @test */
    public function only_manager_can_update_tournament_course_hole_notes()
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make([
            'holes' => 18
        ]);

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $tournamentCourse = $tournament->courses->first();

        $this->actingAs($this->createUser())
            ->json('PUT', 'tournament/course/holes/' . $tournamentCourse->id, [
                'notes[1]' => 'All the OB',
                'notes[4]' => 'Mandatory to the right'
            ])
            ->assertStatus(403);
    }

    /** @test */
    public function manager_can_update_caddy_book_information()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $course = factory(Course::class)->make([
            'holes' => 18
        ]);

        $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $course->toArray());

        $tournamentCourse = $tournament->courses->first();

        $data = [
            'notes' => [
                1 => 'All the OB',
                2 => 'Mandatory to the right'
            ]
        ];

        $this->actingAs($user)
            ->json('PUT', 'tournament/course/holes/' . $tournamentCourse->id, $data);

        $holes = TournamentCourseHole::all();

        $this->assertEquals($data['notes'][1], $holes->where('hole', 1)->first()['notes']);
        $this->assertEquals($data['notes'][2], $holes->where('hole', 2)->first()['notes']);
    }
}
