<?php

namespace Tests;

use DGTournaments\Models\Order;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp()
    {
        parent::setUp();

        config(['scout.driver' => null]);

        if(Schema::hasTable('flag_types')) {
            $this->seed('FlagTypesSeeder');
        }
    }

    /**
     * @return User
     */
    protected function createUser()
    {
        return factory(User::class)->create();
    }

    /**
     * @return Tournament
     */
    protected function createTournament()
    {
        return factory(Tournament::class)->create();
    }

    /**
     * @return Order
     */
    protected function createOrder()
    {
        return factory(Order::class)->create();
    }

    protected function createTournamentWithManager()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        return [$user, $tournament];
    }

    protected function checkGuestAccess($method, $endpoint)
    {

        $this->json($method, $endpoint)->assertStatus(401);
    }

    protected function checkManagerAccess($method, $endpoint)
    {

        $this->actingAs($this->createUser())->json($method, $endpoint)->assertStatus(403);
    }
}
