<?php

/**
 * Test cases:
 *
 * Just a way to automatically trigger the test controller
 */

namespace tests\Feature\Central;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestControllerTest extends TestCase {

	// Clean up the database
	// Not refreshing the database may break others tests
	use DatabaseTransactions;

	function __construct(?string $name = null) {
		parent::__construct($name);

		// required to be able to use the factory inside the constructor
		$this->createApplication();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory()->make();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete();
	}


	/**
	 */
	public function test_index_page() {
		$this->be($this->user);

		// main test entry
		$response = $this->get('/test');
		$response->assertStatus(200);
		$response->assertSeeText('Test page');
	}

	/**
	 * Disabled because phpinfo take a lot of space in the test logs
	 */
	public function ttest_info() {
		$this->be($this->user);

		// main test entry
		$response = $this->get('/info');
		$response->assertStatus(200);
	}
}
