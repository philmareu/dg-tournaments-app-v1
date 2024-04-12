<?php

namespace Tests\Feature\Endpoints;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserCurrentEndpointTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function should_return_null_when_user_is_not_authenticated()
    {
        $response = $this->json('GET', 'user/current');

        $this->assertNull($response->getOriginalContent());
    }

    /** @test */
    public function should_return_user_when_user_is_authenticated()
    {
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $this->be($user);

        $this->json('GET', 'user/current')
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'managing' => [],
                'following_tournaments' => []
            ]);
    }
}
