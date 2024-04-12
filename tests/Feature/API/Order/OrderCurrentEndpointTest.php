<?php

namespace Tests\Feature\Endpoints\Order;

use DGTournaments\Models\Order;
use DGTournaments\Models\OrderSponsorshipProduct;
use DGTournaments\Models\Sponsorship;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderCurrentEndpointTest extends TestCase
{
    use DatabaseMigrations;

    protected $endpoint = 'order/current';

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */

    /**
     * @test
     */
    public function endpoint_is_available()
    {
        $this->call('GET', $this->endpoint)
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function should_return_null_if_no_current_order_is_available()
    {
        $response = $this->call('GET', $this->endpoint);

        $this->assertNull($response->getOriginalContent());
    }
}
