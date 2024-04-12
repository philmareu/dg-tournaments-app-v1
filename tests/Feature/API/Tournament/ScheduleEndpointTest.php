<?php

namespace Tests\Feature\Manager\Tournament;

use Carbon\Carbon;
use DGTournaments\Models\Schedule;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\TournamentSchedule;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScheduleEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function only_manager_can_store_new_schedule_item()
    {

        $this->actingAs($this->createUser())
            ->json('POST', 'tournament/schedule/' . factory(Tournament::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function only_manager_can_edit_schedule_items()
    {

        $this->actingAs($this->createUser())
            ->json('PUT', 'tournament/schedule/' . factory(Schedule::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function only_manager_can_delete_schedule_items()
    {

        $this->actingAs($this->createUser())
            ->json('DELETE', 'tournament/schedule/' . factory(Schedule::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function manager_can_store_new_schedule_item()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $data = [
            'date' => '1-1-2000',
            'start' => '1:30 PM',
            'end' => '2:30 PM',
            'summary' => 'Players meeting',
            'location' => 'Main course'
        ];

        $this->actingAs($user)
            ->json('POST', 'tournament/schedule/' . $tournament->id, $data)
            ->assertJson([
                'Saturday (1st)' => []
            ]);

        $scheduleItem = $tournament->schedule->first();

        $this->assertInstanceOf(Carbon::class, $scheduleItem->date);
        $this->assertInstanceOf(Carbon::class, $scheduleItem->start);
        $this->assertInstanceOf(Carbon::class, $scheduleItem->end);
        $this->assertEquals('2000-01-01', $scheduleItem->date->format('Y-m-d'));
        $this->assertEquals('13:30:00', $scheduleItem->start->format('H:i:s'));
        $this->assertEquals('14:30:00', $scheduleItem->end->format('H:i:s'));
        $this->assertEquals($data['summary'], $scheduleItem->summary);
        $this->assertEquals($data['location'], $scheduleItem->location);
    }

    /** @test */
    public function manager_can_update_schedule_item()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $tournament->schedule()->save(factory(Schedule::class)->make());

        $data = [
            'date' => '1-1-2000',
            'start' => '1:30 PM',
            'end' => '2:30 PM',
            'summary' => 'Players meeting',
            'location' => 'Main course'
        ];

        $this->actingAs($user)
            ->json('PUT', 'tournament/schedule/' . $tournament->schedule->first()->id, $data)
            ->assertJson([
                'Saturday (1st)' => []
            ]);

        $scheduleItem = $tournament->fresh()->schedule->first();

        $this->assertInstanceOf(Carbon::class, $scheduleItem->date);
        $this->assertInstanceOf(Carbon::class, $scheduleItem->start);
        $this->assertInstanceOf(Carbon::class, $scheduleItem->end);
        $this->assertEquals('2000-01-01', $scheduleItem->date->format('Y-m-d'));
        $this->assertEquals('13:30:00', $scheduleItem->start->format('H:i:s'));
        $this->assertEquals('14:30:00', $scheduleItem->end->format('H:i:s'));
        $this->assertEquals($data['summary'], $scheduleItem->summary);
        $this->assertEquals($data['location'], $scheduleItem->location);
    }

    /** @test */
    public function manager_can_delete_a_schedule_item()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $tournament->schedule()->save(factory(Schedule::class)->make());

        $this->actingAs($user)
            ->json('DELETE', 'tournament/schedule/' . $tournament->schedule->first()->id)
            ->assertJson([]);

        $this->assertEquals(0, $tournament->fresh()->schedule->count());
    }

    /** @test */
    public function retrieves_an_ordered_tournament_schedule()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $this->actingAs($user)
            ->json('POST', 'tournament/schedule/' . $tournament->id, [
                'date' => '1-1-2000',
                'start' => '12:30 PM',
                'end' => '2:30 PM',
                'summary' => 'Lunch',
                'location' => 'Main course'
            ]);

        $this->actingAs($user)
            ->json('POST', 'tournament/schedule/' . $tournament->id, [
                'date' => '1-1-2000',
                'start' => '9:00 AM',
                'end' => '9:30 AM',
                'summary' => 'Player meeting',
                'location' => 'Main course'
            ]);

        $this->actingAs($user)
            ->json('POST', 'tournament/schedule/' . $tournament->id, [
                'date' => '1-2-2000',
                'start' => '8:00 AM',
                'end' => '9:00 AM',
                'summary' => 'Warm up',
                'location' => 'Main course'
            ]);

        $this->actingAs($user)
            ->json('POST', 'tournament/schedule/' . $tournament->id, [
                'date' => '1-2-2000',
                'start' => '9:00 AM',
                'end' => '10:00 AM',
                'summary' => 'Start',
                'location' => 'Main course'
            ]);

        $response = $this->json('GET', 'tournament/schedule/' . $tournament->id);

        $this->assertEquals($response->getOriginalContent()->keys()->toArray(), [
            '2000-01-01',
            '2000-01-02'
        ]);

        $this->assertEquals($response->getOriginalContent()->flatten()->pluck('id')->toArray(), [2, 1, 3, 4]);
    }
}
