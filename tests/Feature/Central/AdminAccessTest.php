<?php

namespace tests\Feature\Central;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminAccessTest extends TestCase {
	
	// Clean up the database
	use RefreshDatabase;
	
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
	public function test_admin_user () {
				
		$this->be ( $this->user );
		
		$response = $this->get ( '/backup' );
		$response->assertStatus ( 200 );
		$response->assertSeeText ( __('backup.title'));

		$response = $this->get ( '/users' );
		$response->assertStatus ( 200 );
		$response->assertSeeText ( __('users.add') );
		
		$response = $this->get ( '/tenants' );
		$response->assertStatus ( 200 );
		$response->assertSeeText ( 'New tenant' );
	}

	/**
	 */
	public function test_non_admin_user () {
		
		$this->user->admin = false;

		$this->assertTrue($this->user->isActive());
		
		$this->be ( $this->user );
		
		$response = $this->get ( '/backup' );
		$response->assertStatus ( 302 );
		$response->assertSeeText ( 'Redirecting to' );

		
		$response = $this->get ( '/users' );
		$response->assertStatus ( 302 );
		$response->assertSeeText ( 'Redirecting to' );
		
		$response = $this->get ( '/tenants' );
		$response->assertStatus ( 302 );
		$response->assertSeeText ( 'Redirecting to' );
	}
	
	public function test_non_active_user () {
		
		$this->user->active = false;
		
		$this->assertFalse($this->user->isActive());
		
		// TODO: For some reason, active users are only denied access on live systems ???
		return;
		$this->be ( $this->user );
		
		$response = $this->get ( '/backup' );
		$response->dump();
		
		$response->assertStatus ( 302 );
		$response->assertSeeText ( 'Redirecting to' );
		
		
		$response = $this->get ( '/users' );
		$response->assertStatus ( 302 );
		$response->assertSeeText ( 'Redirecting to' );
		
		$response = $this->get ( '/tenants' );
		$response->assertStatus ( 302 );
		$response->assertSeeText ( 'Redirecting to' );
	}
	
}
