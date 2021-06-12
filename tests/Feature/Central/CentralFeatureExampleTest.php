<?php

namespace Tests\Feature\Central;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CentralFeatureExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
    	$tenant = tenant('id');
    	$this->assertNull($tenant, "no tenant defined");
   
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
