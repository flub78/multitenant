<?php

namespace Tests\Feature\Tenant;

use Tests\TenantTestCase;

class TenantCommandTest extends TenantTestCase
{
	protected $tenancy = true;
	
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
    	$tenant = tenant('id');
    	
    	$this->assertNotNull($tenant, "a tenant is defined");
    	
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    
}
