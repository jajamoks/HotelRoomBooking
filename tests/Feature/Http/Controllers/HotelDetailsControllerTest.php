<?php

namespace Tests\Feature\Http\Controllers;

use App\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;

class HotelDetailsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {

        parent::setUp();
        $this->faker = Faker::create();

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
     * get all hotel details
     *
     * @return void
     */
    public function testGetHotelDetails()
    {

        Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );

        $response = $this->get('/api/hotel');

        $response->assertStatus(200)->assertJsonStructure(['data']);
    }

    /**
     * store hotel details
     *
     * @return void
     */
    public function testStoreHotel()
    {

        Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );

        $response = $this->json('POST', '/api/hotel/create', [
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'zip_code' => $this->faker->postcode,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $response->assertStatus(200)->assertJsonStructure(['code', 'msg']);
    }
}
