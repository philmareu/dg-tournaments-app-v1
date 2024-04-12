<?php

namespace Tests\Unit\Data;

use DGTournaments\Data\Location;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationDataObjectTest extends TestCase
{
    /**
     * @test
     */
    public function should_show_formatted_address()
    {
        $location = new Location('TestVille', 'USA', 'Kansas');

        $this->assertEquals('TestVille, Kansas (USA)', $location->formatted());
    }
}
