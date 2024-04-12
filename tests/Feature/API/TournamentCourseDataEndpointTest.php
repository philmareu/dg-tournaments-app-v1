<?php

namespace Tests\Feature\Endpoints;

use DGTournaments\Models\TournamentCourse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TournamentCourseDataEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function loads_all_course_information_needed_for_the_show_page()
    {
        $tournamentCourse = factory(TournamentCourse::class)->create();

        $this->json('GET', 'tournament/courses/' . $tournamentCourse->id)
            ->assertJson([
                'data' => [
                    'id' => $tournamentCourse->id,
                    'tournament' => [
                        'id' => $tournamentCourse->tournament->id,
                        'managers' => []
                    ]
                ]
            ]);
    }
}
