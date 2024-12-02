<?php

namespace Tests\Feature\Tenant;

use Tests\TenantTestCase;

class TenantFeatureExampleTest extends TenantTestCase {
    protected $tenancy = true;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example() {
        $tenant = tenant('id');

        $this->assertNotNull($tenant, "a tenant is defined");

        $response = $this->get('/');

        $response->assertSee('Welcome to ' . $tenant, "Found tenant name in the page");

        $response->assertStatus(200);
    }
}
