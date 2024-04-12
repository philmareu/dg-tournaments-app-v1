<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\TournamentCourse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ValidationHelperTrait;

class CourseEndpointValidationTest extends TestCase
{
    use DatabaseMigrations, ValidationHelperTrait;

    /** @test */
    public function storing_a_tournament_course_requires_a_name()
    {
        $response = $this->storing();

        $this->assertArrayHasKey('holes', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_tournament_course_requires_a_holes_field()
    {
        $response = $this->storing();

        $this->assertArrayHasKey('holes', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_tournament_course_the_holes_field_must_be_numeric()
    {
        $response = $this->storing(['holes' => 'X']);

        $this->assertArrayHasKey('holes', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_tournament_course_the_holes_field_must_be_less_than_100()
    {
        $response = $this->storing(['holes' => 101]);

        $this->assertArrayHasKey('holes', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_tournament_course_requires_a_latitude()
    {
        $response = $this->storing();

        $this->assertArrayHasKey('latitude', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_tournament_course_the_latitude_must_be_numeric()
    {
        $response = $this->storing(['latitude' => 'X']);

        $this->assertArrayHasKey('latitude', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_tournament_course_requires_a_longitude()
    {
        $response = $this->storing();

        $this->assertArrayHasKey('longitude', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_tournament_course_the_longitude_must_be_numeric()
    {
        $response = $this->storing(['longitude' => 'X']);

        $this->assertArrayHasKey('longitude', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_tournament_course_requires_a_city()
    {
        $response = $this->storing();

        $this->assertArrayHasKey('city', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function storing_a_tournament_course_requires_a_country()
    {
        $response = $this->storing();

        $this->assertArrayHasKey('country', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_requires_a_name()
    {
        $response = $this->updating();

        $this->assertArrayHasKey('name', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_requires_a_holes_field()
    {
        $response = $this->updating();

        $this->assertArrayHasKey('holes', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_the_holes_field_must_be_numeric()
    {
        $response = $this->updating(['holes' => 'X']);

        $this->assertArrayHasKey('holes', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_the_holes_field_must_be_less_than_100()
    {
        $response = $this->updating(['holes' => 101]);

        $this->assertArrayHasKey('holes', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_requires_a_latitude()
    {
        $response = $this->updating();

        $this->assertArrayHasKey('latitude', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_the_latitude_must_be_numeric()
    {
        $response = $this->updating(['latitude' => 'X']);

        $this->assertArrayHasKey('latitude', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_requires_a_longitude()
    {
        $response = $this->updating();

        $this->assertArrayHasKey('longitude', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_the_longitude_must_be_numeric()
    {
        $response = $this->updating(['longitude' => 'X']);

        $this->assertArrayHasKey('longitude', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_requires_a_city()
    {
        $response = $this->updating();

        $this->assertArrayHasKey('city', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function updating_a_tournament_course_requires_a_country()
    {
        $response = $this->updating();

        $this->assertArrayHasKey('country', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function when_storing_a_tournament_course_with_course_id_it_must_exist()
    {
        $response = $this->storing(['course_id' => 10]);

        $this->assertArrayHasKey('course_id', $response->getOriginalContent()['errors']);
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function storing($data = [])
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        return $this->actingAs($user)
            ->json('POST', 'tournament/courses/' . $tournament->id, $data);
    }

    public function updating($data = [])
    {

        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $tournamentCourse = factory(TournamentCourse::class)->create([
            'tournament_id' => $tournament->id
        ]);

        return $this->actingAs($user)
            ->json('PUT', 'tournament/courses/' . $tournamentCourse->id, $data);
    }
}
