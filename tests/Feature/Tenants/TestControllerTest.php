<?php

/**
 * Test for the test controller
 *
 */
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;

class TestControllerTest extends TenantTestCase {
	
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


	public function test_index() {
		$this->be ( $this->user );
		
		// configuration list
		$url = 'http://' . tenant('id'). '.tenants.com/test' ;
		
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		
		// $response->dump();
	}
	
	public function test_email() {
		$this->be ( $this->user );
		
		$url = 'http://' . tenant('id'). '.tenants.com/test/email' ;
		
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		$response->assertSeeText('Email was sent');
		// $response->dump();		
	}
	
}
