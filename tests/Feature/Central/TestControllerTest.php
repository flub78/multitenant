<?php

/**
 * Test cases:
 *
 * Just a way to automatically trigger the test controller
 */
namespace tests\Feature\Central;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestControllerTest extends TestCase {

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
	public function test_index_page() {
		$this->be ( $this->user );
	
		// main test entry
		$response = $this->get ( '/test' );
		$response->assertStatus ( 200 );
		$response->assertSeeText('Test page');		
	}
	
	public function test_info() {
		$this->be ( $this->user );
		
		// main test entry
		$response = $this->get ( '/info' );
		$response->assertStatus ( 200 );
		$response->assertSeeText('PHP Version 8');		
		$response->assertSeeText('HTTP Headers Information');
	}
	
}
