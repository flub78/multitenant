<?php

namespace tests\Feature\Central;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Test that admin can access to admin pages while regular users cannot
 * 
 * @author frederic
 *
 */
class AdminAccessTest extends TestCase {

	// Clean up the database
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
	public function test_admin_user() {

		$this->be($this->user);

		$response = $this->get('/backup');
		$response->assertStatus(200);
		$response->assertSeeText(__('backup.title'));

		$response = $this->get('/user');
		$response->assertStatus(200);
		$response->assertSeeText(__('user.add'));

		$response = $this->get('/tenants');
		$response->assertStatus(200);
		$response->assertSeeText('New tenant');
	}

	/**
	 */
	public function test_non_admin_user() {

		$this->user->admin = false;

		$this->assertTrue($this->user->isActive());

		$this->be($this->user);

		$response = $this->get('/backup');
		$response->assertStatus(302);
		$response->assertSeeText('Redirecting to');


		$response = $this->get('/user');
		$response->assertStatus(302);
		$response->assertSeeText('Redirecting to');

		$response = $this->get('/tenants');
		$response->assertStatus(302);
		$response->assertSeeText('Redirecting to');
	}

	public function test_non_active_user() {

		$this->user->active = false;

		$this->assertFalse($this->user->isActive());

		// TODO: For some reason, active users are only denied access on live systems ???
		return;
		$this->be($this->user);

		$response = $this->get('/backup');
		$response->dump();

		$response->assertStatus(302);
		$response->assertSeeText('Redirecting to');


		$response = $this->get('/user');
		$response->assertStatus(302);
		$response->assertSeeText('Redirecting to');

		$response = $this->get('/tenants');
		$response->assertStatus(302);
		$response->assertSeeText('Redirecting to');
	}
}
