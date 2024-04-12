<?php

namespace Tests\Feature\Manager\Tournament;

use DGTournaments\Models\Tournament;
use DGTournaments\Models\Upload;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MediaEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function only_a_manager_can_store_a_upload()
    {

        $this->actingAs($this->createUser())
            ->json('POST', 'tournament/media/' . factory(Tournament::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function only_a_manager_can_update_a_upload()
    {

        $this->actingAs($this->createUser())
            ->json('PUT', 'tournament/media/' . factory(Tournament::class)->create()->id)
            ->assertStatus(403);
    }

    /** @test */
    public function only_a_manager_can_delete_a_upload()
    {
        $tournament = $this->createTournament();
        $upload = factory(Upload::class)->create();
        $tournament->media()->attach($upload->id);

        $this->actingAs($this->createUser())
            ->json('DELETE', 'tournament/media/' . $tournament->id . '/' . $upload->id)
            ->assertStatus(403);
    }

    /** @test */
    public function manager_can_store_a_new_media_file()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $upload = factory(Upload::class)->create();

        $data = [
            'title' => 'Upload Title',
            'uploaded_id' => $upload->id
        ];

        $this->actingAs($user)
            ->json('POST', 'tournament/media/' . $tournament->id, $data)
            ->assertJson([
                [
                    'id' => $upload->id,
                    'title' => 'Upload Title'
                ]
            ]);

        $this->assertDatabaseHas('media', [
            'tournament_id' => $tournament->id,
            'upload_id' => $upload->id
        ]);
    }

    /** @test */
    public function manager_can_update_a_media_file()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $upload = factory(Upload::class)->create();
        $tournament->media()->save($upload);

        $newUpload = factory(Upload::class)->create();

        $data = [
            'title' => 'Upload Title',
            'id' => $upload->id,
            'uploaded_id' => $newUpload->id
        ];

        $this->actingAs($user)
            ->json('PUT', 'tournament/media/' . $tournament->id, $data)
            ->assertJson([
                [
                    'id' => $newUpload->id,
                    'title' => 'Upload Title',
                ]
            ]);

        $this->assertDatabaseHas('media', [
            'tournament_id' => $tournament->id,
            'upload_id' => $newUpload->id
        ]);
    }

    /** @test */
    public function manager_can_delete_a_media_item()
    {
        list($user, $tournament) = $this->createTournamentWithManager();

        $upload = factory(Upload::class)->create();

        $tournament->media()->save($upload);

        $this->actingAs($user)
            ->json('DELETE', 'tournament/media/' . $tournament->id . '/' . $upload->id)
            ->assertJson([]);

        $this->assertDatabaseMissing('media', [
            'upload_id' => $upload->id,
            'tournament_id' => $tournament->id
        ]);
    }
}
