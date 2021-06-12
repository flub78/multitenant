<?php

namespace tests\Feature\Central;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase {
	
	protected $basename = "users";
	
	// Clean up the database
	use RefreshDatabase;
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->create ();
	}

	function __destruct() {
		// $this->user->delete ();
	}
	

	/**
	 * Index view
	 *
	 * @return void
	 */
	public function test_active_users_can_login() {
		$this->assertTrue(true);

		$this->be ( $this->user );
		$response = $this->get ( '/login' );
		$response->assertStatus ( 302);
		$response->assertSeeText ( 'Redirecting' );
		$response->assertSeeText ( 'home' );
	}		
	
	/**
	 * Index view
	 *
	 * @return void
	 */
	public function ttest_disabled_users_cannot_login() {
		
		$this->user->active = false;
		
		$this->be ( $this->user );
		$response = $this->get ( '/users' );
		$response->assertStatus ( 200 );
		$response->assertSeeText ( 'Multi Central' );
		$response->assertSeeText ( 'Edit' );
	}
	
}
