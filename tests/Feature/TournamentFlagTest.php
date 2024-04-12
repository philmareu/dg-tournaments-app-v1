<?php

namespace Tests\Feature;

use DGTournaments\Models\Registration;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\TournamentCourse;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TournamentFlagTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function lat_and_lng_flag_is_added_to_tournament_with_no_lat_and_lng()
    {
        $tournament = factory(Tournament::class)->create([
            'latitude' => null,
            'longitude' => null
        ]);

        $this->assertDatabaseHas('flags', [
            'tournament_id' => $tournament->id,
            'flag_type_id' => 1
        ]);
    }

    /**
     * @test
     */
    public function needs_course_flag_added_by_default_to_new_tournament()
    {
        $tournament = factory(Tournament::class)->create([
            'latitude' => null,
            'longitude' => null
        ]);

        $this->assertDatabaseHas('flags', [
            'tournament_id' => $tournament->id,
            'flag_type_id' => 2
        ]);
    }

    /**
     * @test
     */
    public function needs_registration_link_flag_added_by_default_to_new_tournament()
    {
        $tournament = factory(Tournament::class)->create([
            'latitude' => null,
            'longitude' => null
        ]);

        $this->assertDatabaseHas('flags', [
            'tournament_id' => $tournament->id,
            'flag_type_id' => 3
        ]);
    }

    /**
     * @test
     */
    public function maintenance_script_flag_tournaments_will_create_flags_for_tournaments_missing_lat_and_lng()
    {
        Event::fake();

        $tournamentsNoGeo = factory(Tournament::class, 3)->states('no-geo', 'future')->create();
        $tournamentsWithGeo = factory(Tournament::class, 3)->states('future')->create();

        $this->artisan('maintenance:flag-tournaments');

        $tournamentsNoGeo->each(function(Tournament $tournament) {
            $this->assertDatabaseHas('flags', [
                'tournament_id' => $tournament->id,
                'flag_type_id' => 1
            ]);
        });

        $tournamentsWithGeo->each(function(Tournament $tournament) {
            $this->assertDatabaseMissing('flags', [
                'tournament_id' => $tournament->id,
                'flag_type_id' => 1
            ]);
        });

    }

    /**
     * @test
     */
    public function maintenance_script_flag_tournaments_will_create_flags_for_tournaments_missing_courses()
    {
        Event::fake();

        $tournamentsNoCourses = factory(Tournament::class, 3)->create();
        $tournamentsWithCourses = factory(Tournament::class, 3)->create()->each(function(Tournament $tournament) {
            $tournament->courses()->save(factory(TournamentCourse::class)->make());
        });

        $this->artisan('maintenance:flag-tournaments');

        $tournamentsNoCourses->each(function(Tournament $tournament) {
            $this->assertDatabaseHas('flags', [
                'tournament_id' => $tournament->id,
                'flag_type_id' => 2
            ]);
        });

        $tournamentsWithCourses->each(function(Tournament $tournament) {
            $this->assertDatabaseMissing('flags', [
                'tournament_id' => $tournament->id,
                'flag_type_id' => 2
            ]);
        });

    }

    /**
     * @test
     */
    public function maintenance_script_flag_tournaments_will_create_flags_for_tournaments_missing_registration()
    {
        Event::fake();

        $tournamentsNoRegistration = factory(Tournament::class, 3)->create();
        $tournamentsWithRegistration = factory(Tournament::class, 3)->create()->each(function(Tournament $tournament) {
            $tournament->registration()->save(factory(Registration::class)->make());
        });

        $this->artisan('maintenance:flag-tournaments');

        $tournamentsNoRegistration->each(function(Tournament $tournament) {
            $this->assertDatabaseHas('flags', [
                'tournament_id' => $tournament->id,
                'flag_type_id' => 3
            ]);
        });

        $tournamentsWithRegistration->each(function(Tournament $tournament) {
            $this->assertDatabaseMissing('flags', [
                'tournament_id' => $tournament->id,
                'flag_type_id' => 3
            ]);
        });

    }
}
