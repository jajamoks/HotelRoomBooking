<?php

namespace Tests\Feature\Http\Controllers;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RoomTypeControllerTest extends TestCase
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
     * get all room type details
     *
     * @return void
     */
    public function testGetRoomTypeDetails()
    {

        Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );

        $response = $this->get('/api/roomtype');

        $response->assertStatus(200)->assertJsonStructure(['data']);
    }
}
