<?php

/**
 * Test cases:
 *
 * Nominal: list backups, create a new one, checks that it exists and delete it.
 *
 * Error test case:
 * delete a non existing backup
 * restore a non existing backup
 *
 * attempt to create, restore or delete a backup as non admin
 */
namespace tests\Feature\Central;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CentralHomeControllerTest extends TestCase {

	// Clean up the database
	// Not refreshing the database may break others tests
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
	public function test_home() {
		$this->be ( $this->user );

		// Home page
		$response = $this->get ( '/home' );
		$response->assertStatus ( 200 );
		$response->assertSeeText('Central Application');
		$response->assertSeeText('Dashboard');		
	}

}
