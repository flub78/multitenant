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
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		
		// create save the instance in database, make just creates a new instance
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete ();
	}
	
	public function test_url() {
		$id = tenant('id');
		
		$tnt = Tenant::findOrFail ( $id );		
		$url = $tnt->url();
		
		$this->assertEquals("http://test.tenants.com", $url);
	}
	
}
