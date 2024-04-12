<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PageLoadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

//        Artisan::call('db:seed');
    }

    /**
     * @dataProvider provideGuestRoutes
     */
    public function testGuestPageLoads($method, $route)
    {
        $response = $this->call($method, $route);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function provideGuestRoutes()
    {
        return [
            ['GET', '/'],
//            ['GET', 'disc-golf-tournament/1/dgt-open-sanctioned']
        ];
    }

//    /**
//     * @dataProvider provideAuthRoutes
//     */
//    public function testAuthPageLoads($method, $route)
//    {
//        $user = $this->createUser();
//
//        $response = $this->actingAs($user)
//            ->call($method, $route);
//
//        $this->assertEquals(200, $response->getStatusCode());
//    }
//
//    public function provideAuthRoutes()
//    {
//        return [
//
//        ];
//    }
}
