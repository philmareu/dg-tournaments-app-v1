<?php

namespace Tests\Feature\Endpoints\Order;

use DGTournaments\Models\OrderSponsorship;
use DGTournaments\Models\User\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessCustomerEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /**
     * @test
     */
    public function during_checkout_guest_details_are_saved_on_order()
    {
        $orderProduct = factory(OrderSponsorship::class)->create();

        $data = [
            'unique' => $orderProduct->order->unique,
            'email' => 'test@test.com',
            'first_name' => 'Test',
            'last_name' => 'Ing'
        ];

        $this->json('PUT', 'order/checkout/details', $data);

        $this->assertDatabaseHas('orders', $data);
    }

    /**
     * @test
     */
    public function during_checkout_a_guest_can_create_a_new_user_account()
    {
        $orderProduct = factory(OrderSponsorship::class)->create();

        $data = [
            'unique' => $orderProduct->order->unique,
            'email' => 'test@test.com',
            'first_name' => 'Test',
            'last_name' => 'Ing',
            'password' => 'password',
            'create_account' => 1
        ];

        $this->json('PUT', 'order/checkout/details', $data);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
            'first_name' => 'Test',
            'last_name' => 'Ing',
            'name' => 'Test Ing'
        ]);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_not_create_an_account()
    {
        $orderProduct = factory(OrderSponsorship::class)->create();

        $data = [
            'unique' => $orderProduct->order->unique,
            'email' => 'test@test.com',
            'first_name' => 'Test',
            'last_name' => 'Ing',
            'password' => 'password',
            'create_account' => 1
        ];

        $this->actingAs(factory(User::class)->create())
            ->json('PUT', 'order/checkout/details', $data);

        $this->assertDatabaseMissing('users', [
            'email' => 'test@test.com',
            'first_name' => 'Test',
            'last_name' => 'Ing',
            'name' => 'Test Ing'
        ]);
    }

    /**
     * @test
     */
    public function the_order_is_returned_after_saving_customer_details()
    {
        $orderProduct = factory(OrderSponsorship::class)->create();

        $data = [
            'unique' => $orderProduct->order->unique,
            'email' => 'test@test.com',
            'first_name' => 'Test',
            'last_name' => 'Ing'
        ];

        $this->json('PUT', 'order/checkout/details', $data)
            ->assertJson([
                'unique' => $orderProduct->order->unique,
                'sponsorships' => []
            ]);
    }

    /**
     * @test
     */
    public function the_user_is_returned_after_creating_a_new_user_details()
    {
        $orderProduct = factory(OrderSponsorship::class)->create();

        $data = [
            'unique' => $orderProduct->order->unique,
            'email' => 'test@test.com',
            'first_name' => 'Test',
            'last_name' => 'Ing',
            'password' => 'password',
            'create_account' => 1
        ];

        $this->json('PUT', 'order/checkout/details', $data)
            ->assertJson([
                'unique' => $orderProduct->order->unique,
                'sponsorships' => [],
                'user' => []
            ]);
    }
}
