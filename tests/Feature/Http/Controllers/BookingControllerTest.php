<?php

namespace Tests\Feature\Http\Controllers;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
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
     * get all booking details
     *
     * @return void
     */
    public function testGetBookingDetails()
    {

        Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );

        $response = $this->get('/api/booking');

        $response->assertStatus(200)->assertJsonStructure(['data']);
    }
}
