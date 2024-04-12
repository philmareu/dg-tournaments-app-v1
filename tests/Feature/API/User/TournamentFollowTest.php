<?php

namespace Tests\Feature;

use DGTournaments\Events\TournamentFollowed;
use DGTournaments\Events\TournamentUnfollowed;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TournamentFollowTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function guest_cannot_follow_a_tournament()
    {
        $tournament = $this->createTournament();

        $this->assertFalse(Auth::check());
        $response = $this->json('PUT', 'user/follow/tournament/' . $tournament->id);
        $response->assertStatus(401);
    }


    /**
     * @test
     */
    public function user_can_follow_a_tournament()
    {
        list($user, $tournament) = $this->createUserFollowTournament();

        $followingTournament = $user->following()
            ->where('resource_type', 'DGTournaments\Models\Tournament')
            ->where('resource_id', $tournament->id)
            ->first();

        $this->assertEquals($tournament->id, $followingTournament->id);
    }

    /**
     * @test
     */
    public function user_model_is_returned_with_following_relationship_after_a_follow()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();

        $this->actingAs($user)
            ->json('PUT', '/user/follow/tournament/' . $tournament->id)
            ->assertJson([
                'following' => []
            ]);
    }

    /**
     * @test
     */
    public function user_can_unfollow_a_tournament()
    {
        list($user, $tournament) = $this->createUserFollowTournament();
        $this->followTournament($user, $tournament);

        $followingTournament = $user->following()
            ->where('resource_type', 'DGTournaments\Models\Tournament')
            ->where('resource_id', $tournament->id)
            ->first();

        $this->assertEquals(null, $followingTournament);
    }

    /** @test */
    public function followed_tournament_event_is_fired()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        Event::fake();

        $this->followTournament($user, $tournament);

        Event::assertDispatched(TournamentFollowed::class, function ($e) use ($tournament) {
            return $e->follow->resource->id === $tournament->id;
        });
    }

    /** @test */
    public function unfollowed_tournament_event_is_fired()
    {
        list($user, $tournament) = $this->createUserFollowTournament();

        Event::fake();
        $this->followTournament($user, $tournament);

        Event::assertDispatched(TournamentUnfollowed::class, function ($e) use ($tournament) {
            return $e->tournament->id === $tournament->id;
        });
    }

    /** @test */
    public function an_activity_is_created_when_tournament_is_followed()
    {
        list($user, $tournament) = $this->createUserFollowTournament();

        $this->assertDatabaseHas('activities', [
            'user_id' => $user->id,
            'resource_type' => 'DGTournaments\Models\Tournament',
            'resource_id' => $tournament->id,
            'type' => 'tournament.followed'
        ]);
    }

    /** @test */
    public function an_activity_is_created_when_tournament_is_unfollowed()
    {
        list($user, $tournament) = $this->createUserFollowTournament();
        $this->followTournament($user, $tournament);

        $this->assertDatabaseHas('activities', [
            'user_id' => $user->id,
            'resource_type' => 'DGTournaments\Models\Tournament',
            'resource_id' => $tournament->id,
            'type' => 'tournament.unfollowed'
        ]);
    }

    /** @test */
    public function user_model_provides_a_list_of_following_tournaments()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $this->followTournament($user, $tournament);

        $tournament = $this->createTournament();
        $this->followTournament($user, $tournament);

        $tournament = $this->createTournament();
        $this->followTournament($user, $tournament);

        $this->assertInstanceOf(Collection::class, $user->followingTournaments);
        $this->assertEquals(3, $user->followingTournaments->count());
    }

    /**
     * @param $user
     * @param $tournament
     */
    public function followTournament($user, $tournament)
    {
        $response = $this->actingAs($user)
            ->json('PUT', '/user/follow/tournament/' . $tournament->id);
    }

    /**
     * @return array
     */
    public function createUserFollowTournament()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $this->followTournament($user, $tournament);

        return [$user, $tournament];
    }

    /** @test */
//    public function creating_tournament_followed_activity_is_queued()
//    {
//        $user = $this->createUser();
//        $tournament = $this->createTournament();
//
//        Queue::fake();
//
//        $this->followTournament($user, $tournament);
//
//        Queue::assertPushed(\DGTournaments\Listeners\Activity\CreateTournamentFollowedActivity::class, function ($job) use ($tournament, $user) {
//            return $job->tournament->id === $tournament->id && $job->user->id === $user->id;
//        });
//    }
}
