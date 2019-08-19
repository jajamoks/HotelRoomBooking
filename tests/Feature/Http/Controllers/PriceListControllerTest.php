<?php

namespace Tests\Feature\Http\Controllers;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PriceListControllerTest extends TestCase
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
     * get all pricelist details
     *
     * @return void
     */
    public function testGetPriceListDetails()
    {

        Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );

        $response = $this->get('/api/pricelist');

        $response->assertStatus(200)->assertJsonStructure(['data']);
    }
}
