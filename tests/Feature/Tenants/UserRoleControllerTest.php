<?php

/**
 * Test cases:
 *
 * Nominal: CRUD testing
 *
 * Error test case:
 *      store of incorrect data is rejected
 * 		delete a non existing role
 *
 * From the Laravel documentation:
 * Unexpected behavior may occur if multiple
 * requests are executed within a single test method.
 */

namespace tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\UserRole;
use App\Models\Tenants\Role;

class UserRoleControllerTest extends TenantTestCase {

	protected $tenancy = true;

	function __construct(?string $name = null) {
		parent::__construct($name);

		// required to be able to use the factory inside the constructor
		$this->createApplication();

		$this->user = User::factory()->create();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete();
	}

	/**
	 * Create an element and returns its id
	 * @return int
	 */
	private function create_first() {
		$this->be($this->user);

		$initial_count = UserRole::count();
		$this->assertTrue($initial_count == 0, "No element after refresh");

		// Create
		$user_role = UserRole::factory()->create();
		$count = UserRole::count();
		$this->assertTrue($count == 1, "One element created");

		# Read
		return ($user_role->id);
	}

	/**
	 * Index view
	 *
	 * @return void
	 */
	public function test_user_roles_index_view() {

		$this->get_tenant_url(
			$this->user,
			'user_role',
			[__('user_role.title'), __('user_role.user_id'), __('user_role.role_id'), __('user_role.add'), tenant('id')]
		);
	}

	/**
	 * Create view
	 *
	 * @return void
	 */
	public function test_user_role_create_view() {
		$this->get_tenant_url(
			$this->user,
			'user_role/create',
			[__('user_role.new'), __('user_role.user_id'), __('user_role.role_id'), tenant('id')]
		);
	}


	/**
	 * Test element storage
	 */
	public function test_user_role_store() {

		// to avoid the error: 419 = Authentication timeout
		$this->withoutMiddleware();

		$initial_count = UserRole::count();

		// create a few roles and users
		$this->user1 = User::factory()->create();

		$this->role1 = Role::factory()->create(['name' => 'redactor']);

		$elt = array('user_id' => $this->user1->id, 'role_id' => $this->role1->id);
		$response = $this->post('/user_role', $elt);

		if (session('errors')) {
			$this->assertTrue(session('errors'), "session has no errors");
		}

		$this->assertTrue(UserRole::count() == $initial_count + 1, "One new elements in the table");
	}

	/**
	 * Test element storage
	 */
	public function test_user_role_store_existing_element() {

		// to avoid the error: 419 = Authentication timeout
		$this->withoutMiddleware();

		$initial_count = UserRole::count();

		// create a few roles and users
		$this->user1 = User::factory()->create();
		$this->role1 = Role::factory()->create(['name' => 'redactor']);

		UserRole::factory()->create(['user_id' => $this->user1->id, 'role_id' => $this->role1->id]);
		$this->assertTrue(UserRole::count() == $initial_count + 1, "One new elements in the table");

		$elt = array('user_id' => $this->user1->id, 'role_id' => $this->role1->id);
		$response = $this->post('/user_role', $elt);

		$this->assertTrue(UserRole::count() == $initial_count + 1, "nothing added on erroneous request");
	}

	/**
	 * Test element storage
	 */
	public function test_user_role_store_values() {

		// to avoid the error: 419 = Authentication timeout
		$this->withoutMiddleware();

		$initial_count = UserRole::count();

		// create a few roles and users
		$this->user1 = User::factory()->create();
		$this->role1 = Role::factory()->create(['name' => 'redactor']);

		UserRole::factory()->make(['user_id' => $this->user1->id, 'role_id' => $this->role1->id]);
		$this->assertTrue(UserRole::count() == $initial_count, "Make does not store anything");

		$elt = array('user_id' => $this->user1->id, 'role_id' => $this->role1->id + 1000000000);
		$response = $this->post('/user_role', $elt);

		$this->assertTrue(UserRole::count() == $initial_count, "nothing added on erroneous request");
	}


	/**
	 * 
	 */
	public function test_user_role_delete() {
		$this->user1 = User::factory()->create();
		$this->role1 = Role::factory()->create(['name' => 'redactor']);

		$user_role = UserRole::factory()->create(['user_id' => $this->user1->id, 'role_id' => $this->role1->id]);

		$initial_count = UserRole::count();
		$this->assertEquals(1, $initial_count, "one element before delete");

		$sub_url = "user_role/" . $user_role->id;
		$this->delete_tenant_url($this->user, $sub_url);
		$this->assertEquals(0, UserRole::count(), "Element deleted ($sub_url)");
	}

	/**
	 * Test element storage
	 */
	public function test_user_role_edit_page() {

		$initial_count = UserRole::count();

		// create a few roles and users
		$this->user1 = User::factory()->create();
		$this->role1 = Role::factory()->create(['name' => 'redactor']);

		$user_role = UserRole::factory()->make(['user_id' => $this->user1->id, 'role_id' => $this->role1->id]);
		$id = $user_role->save();

		$this->get_tenant_url($this->user, 'user_role/' . $id . '/edit', []);
	}

	public function test_update() {

		// create a few roles and users
		$this->user1 = User::factory()->create();
		$this->role1 = Role::factory()->create(['name' => 'redactor']);
		$this->role2 = Role::factory()->create(['name' => 'killer']);

		$elt = ['user_id' => $this->user1->id, 'role_id' => $this->role1->id];
		$user_role = UserRole::factory()->make($elt);
		$id = $user_role->save();

		$elt['role_id'] = $this->role2->id;
		$url = 'user_role/' . $id;

		$this->patch_tenant_url($this->user, 'user_role/' . $id, $elt);
	}
}
