<?php

/**
 * Test cases: url function of the Tenant model
 *
 */

namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenant;

class TenantModelTest extends TenantTestCase {

	protected $tenancy = true;

	function __construct(?string $name = null) {
		parent::__construct($name);

		// required to be able to use the factory inside the constructor
		$this->createApplication();

		// create save the instance in database, make just creates a new instance
		// $this->user = factory(User::class)->create();
		$this->user = User::factory()->make();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete();
	}

	public function test_tenant_url() {
		$id = tenant('id');

		$tnt = Tenant::findOrFail($id);
		$url = $tnt->url();

		$this->assertEquals("http://" . $this->domain("test"), $url);
	}

	public function test_domain() {

		$id = tenant('id');

		$tnt = Tenant::findOrFail($id);

		// Check the number of domains fo rthe current tenant
		$initial_count = $tnt->domains()->count();

		// Add a new domain and check that it has been created
		$tnt->domains()->create(['domain' => 'google.com']);
		$count = $tnt->domains()->count();
		$this->assertEquals($count, $initial_count + 1);

		// Delete it and check that it has been deleted
		$tnt->domains()->where(['domain' => 'google.com'])->delete();
		$count = $tnt->domains()->count();
		$this->assertEquals($count, $initial_count);
	}
}
