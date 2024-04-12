<?php

namespace Tests\Unit;

use DGTournaments\Models\ClaimRequest;
use DGTournaments\Repositories\ClaimRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClaimRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function token_created()
    {
        $repo = $this->getRepo();

        $this->assertEquals('string', gettype($repo->createToken()));
    }

    /** @test */
    public function should_be_false_if_there_is_no_existing_claim_request()
    {
        $repo = $this->getRepo();
        $tournament = $this->createTournament();

        $this->assertFalse($repo->tournamentAlreadyHasRequest($tournament));
    }

    /** @test */
    public function should_be_true_if_there_is_an_existing_claim_request()
    {
        $repo = $this->getRepo();
        $tournament = $this->createTournament();

        $claim = factory(ClaimRequest::class)->make();
        $claim->tournament()->associate($tournament);
        $claim->save();

        $this->assertTrue($repo->tournamentAlreadyHasRequest($tournament));
    }

    /** @test */
    public function should_be_true_if_user_already_can_manage_this_tournament()
    {
        $repo = $this->getRepo();
        $user = $this->createUser();
        $tournament = $this->createTournament();
        $tournament->managers()->save($user);

        $this->assertTrue($repo->userAlreadyManages($tournament, $user));
    }

    /** @test */
    public function should_be_false_if_user_cannot_manager_this_tournament()
    {
        $repo = $this->getRepo();
        $user = $this->createUser();
        $tournament = $this->createTournament();

        $this->assertFalse($repo->userAlreadyManages($tournament, $user));
    }

    /**
     * @return ClaimRepository
     */
    public function getRepo()
    {
        return new ClaimRepository(new ClaimRequest());
    }
}
