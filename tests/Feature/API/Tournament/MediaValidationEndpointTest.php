<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\Upload;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MediaValidationEndpointTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function storing_a_media_requires_a_title()
    {
        $this->storing('title');
    }

    /** @test */
    public function storing_a_media_requires_a_upload_id()
    {
        $this->storing('uploaded_id');
    }

    /** @test */
    public function storing_a_media_requires_a_upload_id_that_exists()
    {
        $this->storing('uploaded_id', ['uploaded_id' => 10]);
    }

    /**
     * @test
     */
    public function updating_a_media_requires_a_title()
    {
        $this->updating('title');
    }

    /**
     * @test
     */
    public function updating_a_media_requires_the_current_upload_id()
    {
        $this->updating('uploaded_id');
    }

    /** @test */
    public function updating_a_media_requires_a_upload_id_that_exists_if_submitted()
    {
        $this->updating('uploaded_id', ['uploaded_id' => 10]);
    }

    private function storing($key, $data = [])
    {

        list($user, $tournament) = $this->createTournamentWithManager();

        $response = $this->actingAs($user)
            ->json('POST', 'tournament/media/' . $tournament->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }

    private function updating($key, $data = [])
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $tournament->media()->attach(factory(Upload::class)->create()->id);

        $response =$this->actingAs($user)
            ->json('PUT', 'tournament/media/' . $tournament->id, $data);

        $this->assertArrayHasKey($key, $response->getOriginalContent()['errors']);
    }
}
