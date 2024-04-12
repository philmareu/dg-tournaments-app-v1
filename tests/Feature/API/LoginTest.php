<?php

namespace Tests\Feature;

use DGTournaments\Models\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_log_in_using_ajax()
    {
        $user = $this->createUser();

        $response = $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->assertArrayHasKey('success', $response->getOriginalContent());
        $response->assertStatus(200);
    }

    /** @test */
    public function user_must_be_activated_to_log_in()
    {
        $user = $this->createUser();
        $user->activated = 0;
        $user->save();

        $response = $this->json('POST', 'login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('errors', $response->getOriginalContent());
    }

    /** @test */
    public function logging_in_fails_with_wrong_email_address()
    {
        $response = $this->json('POST', 'login', [
            'email' => 'bad@email.address',
            'password' => 'password'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('email', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function logging_in_fails_with_wrong_password()
    {

        $user = $this->createUser();

        $response = $this->json('POST', 'login', [
            'email' => $user->email,
            'password' => 'wrong_password'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('errors', $response->getOriginalContent());
    }

    /** @test */
    public function email_address_is_required()
    {


        $response = $this->json('POST', 'login', [
            'password' => 'password'
        ]);

        $this->assertArrayHasKey('email', $response->getOriginalContent()['errors']);
    }

    /** @test */
    public function password_is_required()
    {


        $response = $this->json('POST', 'login', [
            'email' => 'test@test.com'
        ]);

        $this->assertArrayHasKey('password', $response->getOriginalContent()['errors']);
    }

    protected function createUser()
    {
        return factory(User::class)->create([
            'name' => 'Tester McTesterson',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'activated' => 1
        ]);
    }
}
