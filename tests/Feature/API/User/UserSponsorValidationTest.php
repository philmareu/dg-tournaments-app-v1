<?php

namespace Tests\Feature\Manager\Sponsor;

use DGTournaments\Models\Sponsor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserSponsorValidationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function storing_a_sponsor_requires_a_title()
    {
        $this->storing('title');
    }

    /** @test */
    public function storing_a_sponsor_requires_a_valid_url()
    {
        $this->storing('url', ['url' => 'Not a valid url']);
    }

    /** @test */
    public function storing_a_sponsor_requires_a_upload_id_that_exists()
    {
        $this->storing('upload_id', ['upload_id' => 1]);
    }

    /** @test */
    public function updating_a_sponsor_requires_a_title()
    {
        $this->updating('title');
    }

    /** @test */
    public function updating_a_sponsor_requires_a_valid_url()
    {
        $this->updating('url', ['url' => 'Not a valid url']);
    }

    /** @test */
    public function updating_a_sponsor_requires_a_upload_id_that_exists_if_submitted()
    {
        $this->updating('upload_id', ['upload_id' => 10]);
    }

    private function storing($key, $data = [])
    {
        $this->actingAs($this->createUser())
            ->call('POST', 'user/sponsors', $data)
            ->assertSessionHasErrors($key);
    }

    private function updating($key, $data = [])
    {
        $user = $this->createUser();
        $sponsor = factory(Sponsor::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->call('PUT', 'user/sponsors/' . $sponsor->id, $data)
            ->assertSessionHasErrors($key);
    }
}
