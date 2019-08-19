<?php

namespace Tests\Feature\Http\Controllers\API;

use App\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
//use Faker\Generator as Faker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    use DatabaseTransactions;

    public function setUp(): void
    {

        parent::setUp();
        $faker = Faker::create();
        $this->user = factory(User::class)->create();
        $this->user2 = factory(User::class)->create();

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     *
     *  test for duplicate user details
     */
    public function testDuplicateRegistration()
    {

        $response = $this->json('POST', '/api/register', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => 'hotelbooking',
        ]);
        $response->assertStatus(500);
    }
    /**
     * a test with correct user details
     *
     */
    public function testUserLogin()
    {
        $response = $this->json('POST', '/api/login', [
            'username' => "mdari61@gmail.com",
            'password' => "hotelbooking",
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }

    /**
     * a test for wrong username
     */
    public function testInvalidUserLoginDetails()
    {
        $response = $this->json('POST', '/api/login', [
            'username' => 'mdari61@gmaila.com',
            'password' => 'hotelbooking',
        ]);

        $response->assertStatus(422);
    }
}
