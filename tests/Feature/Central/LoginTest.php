<?php

namespace tests\Feature\Central;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Test login
 * 
 * @author frederic
 *
 */
class LoginTest extends TestCase {

	protected $basename = "users";

	// Clean up the database
	use DatabaseTransactions;

	function __construct(?string $name = null) {
		parent::__construct($name);

		// required to be able to use the factory inside the constructor
		$this->createApplication();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory()->create();
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

		$this->be($this->user);
		$response = $this->followingRedirects()->get('/login');
		$response->assertStatus(200);
		$response->assertSeeText('You are logged in');
	}

	/**
	 * Index view
	 *
	 * @return void
	 */
	public function ttest_disabled_users_cannot_login() {

		$this->user->active = false;
		$this->user->admin = true;
		$this->user->save();

		$this->be($this->user);
		$response = $this->followingRedirects()->get('/user');
		$response->assertStatus(200);
		$response->assertSeeText('Multi Central');
		$response->assertSeeText('Edit');
	}
}
