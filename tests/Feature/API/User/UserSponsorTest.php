<?php

namespace Tests\Feature\Manager\Sponsor;

use DGTournaments\Models\Sponsor;
use DGTournaments\Models\Upload;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserSponsorTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $endpoint = 'user/sponsors';

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function guests_can_not_retrieve_sponsors()
    {

        $this->json('GET', $this->endpoint)
            ->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_retrieve_a_list_of_sponsors()
    {
        $user = $this->createUser();
        $sponsors = [
            $user->sponsors()->save(factory(Sponsor::class)->create())->toArray(),
            $user->sponsors()->save(factory(Sponsor::class)->create())->toArray()
        ];

        $this->actingAs($user)
            ->json('GET', $this->endpoint)
            ->assertStatus(200)
            ->assertJson($sponsors);
    }


    /** @test */
    public function only_an_authenticated_user_can_store_a_sponsor()
    {

        $this->json('POST', $this->endpoint)
            ->assertStatus(401);
    }

    /** @test */
    public function only_an_authenticated_user_can_update_a_sponsor()
    {

        $this->json('PUT', $this->endpoint . '/' . factory(Sponsor::class)->create()->id)
            ->assertStatus(401);
    }

    /** @test */
    public function only_an_authenticated_user_can_delete_a_sponsor()
    {

        $this->json('DELETE', $this->endpoint . '/' . factory(Sponsor::class)->create()->id)
            ->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_store_a_new_sponsor()
    {
        $user = $this->createUser();

        $data = [
            'title' => 'Sponsor Title',
            'url' => 'http://testing.com',
            'upload_id' => factory(Upload::class)->create()->id
        ];

        $this->actingAs($user)
            ->json('POST', $this->endpoint, $data);

        $this->assertDatabaseHas('sponsors', array_merge($data, ['user_id' => $user->id]));
    }

    /** @test */
    public function authenticated_user_can_update_a_sponsor()
    {
        $user = $this->createUser();
        $sponsor = factory(Sponsor::class)->create([
            'user_id' => $user->id
        ]);

        $data = [
            'title' => 'Sponsor Title',
            'url' => 'http://testing.com',
            'upload_id' => factory(Upload::class)->create()->id
        ];

        $this->actingAs($user)
            ->json('PUT', 'user/sponsors/' . $sponsor->id, $data);

        $sponsor = $sponsor->fresh();

        $this->assertEquals($data['title'], $sponsor->title);
        $this->assertEquals($data['url'], $sponsor->url);
        $this->assertEquals($data['upload_id'], $sponsor->upload_id);
    }

    /**
     * @test
     */
    public function updating_a_sponsor_request_the_user_to_own_sponsor()
    {
        $user = $this->createUser();
        $sponsor = factory(Sponsor::class)->create();

        $data = [
            'title' => 'Sponsor Title',
            'url' => 'http://testing.com',
            'upload_id' => factory(Upload::class)->create()->id
        ];

        $this->actingAs($user)
            ->json('PUT', 'user/sponsors/' . $sponsor->id, $data)
            ->assertStatus(403);

        $this->assertDatabaseHas('sponsors', [
            'id' => $sponsor->id,
            'title' => $sponsor->title,
            'url' => $sponsor->url,
            'upload_id' => $sponsor->upload_id
        ]);
    }

    /** @test */
    public function authenticated_user_can_soft_delete_a_sponsor()
    {
        $user = $this->createUser();

        $sponsor = factory(Sponsor::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('DELETE', 'user/sponsors/' . $sponsor->id);

        $sponsor = Sponsor::withTrashed()->whereId($sponsor->id)->first();

        $this->assertNotNull($sponsor->deleted_at);
    }
}
