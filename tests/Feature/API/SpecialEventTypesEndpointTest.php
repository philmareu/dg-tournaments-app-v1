<?php

namespace Tests\Feature\Endpoints;

use DGTournaments\Models\SpecialEventType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpecialEventTypesEndpointTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function should_return_list_of_special_event_types()
    {
        factory(SpecialEventType::class)->create(['title' => 'Test title 1']);
        factory(SpecialEventType::class)->create(['title' => 'Test title 2']);

        $this->json('GET', 'lists/special-event-types')
            ->assertJson([
                [
                    'id' => 1,
                    'title' => 'Test title 1'
                ],
                [
                    'id' => 2,
                    'title' => 'Test title 2'
                ]
            ]);
    }
}
