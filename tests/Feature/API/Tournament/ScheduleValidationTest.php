<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\Schedule;
use DGTournaments\Models\TournamentSchedule;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScheduleValidationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function storing_a_schedule_item_requires_a_date()
    {
        $this->storing('date');
    }

    /** @test */
    public function storing_a_schedule_item_requires_a_summary()
    {
        $this->storing('summary');
    }

    /** @test */
    public function storing_a_schedule_item_the_date_must_be_a_certain_format()
    {
        $this->storing('date', ['date' => 'Not valid']);
    }

    /** @test */
    public function storing_a_schedule_item_the_start_time_must_be_a_certain_format()
    {
        $this->storing('start', ['start' => 'Not valid']);
    }

    /** @test */
    public function storing_a_schedule_item_the_end_time_must_be_a_certain_format()
    {
        $this->storing('end', ['end' => 'Not valid']);
    }

    /** @test */
    public function updating_a_schedule_item_requires_a_date()
    {
        $this->updating('date');
    }

    /** @test */
    public function updating_a_schedule_item_requires_a_summary()
    {
        $this->updating('summary');
    }

    /** @test */
    public function updating_a_schedule_item_the_date_must_be_a_certain_format()
    {
        $this->updating('date', ['date' => 'Not valid']);
    }

    /** @test */
    public function updating_a_schedule_item_the_start_time_must_be_a_certain_format()
    {
        $this->updating('start', ['start' => 'Not valid']);
    }

    /** @test */
    public function updating_a_schedule_item_the_end_time_must_be_a_certain_format()
    {
        $this->updating('end', ['end' => 'Not valid']);
    }

    private function storing($key, $data = [])
    {

        list($user, $tournament) = $this->createTournamentWithManager();

        $response = $this->actingAs($user)
            ->json('POST', 'tournament/schedule/' . $tournament->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }

    private function updating($key, $data = [])
    {

        list($user, $tournament) = $this->createTournamentWithManager();
        $tournament->schedule()->save(factory(Schedule::class)->make());

        $response =$this->actingAs($user)
            ->json('PUT', 'tournament/schedule/' . $tournament->schedule->first()->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }
}
