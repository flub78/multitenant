<?php

/**
 * Test cases:
 *
 *    access to tenant home page
 */
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;
use App\Helpers\TenantHelper;

class TenantHomeControllerTest extends TenantTestCase {
	
	protected $tenancy = true;
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete ();
	}


	/**
	 */
	public function test_home() {		
		$this->get_tenant_url($this->user, 'home', [ tenant ('id')]);		
	}	
}
