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
use App\Models\Tenants\Role;
use Illuminate\Support\Facades\Log;

class RoleControllerTest extends TenantTestCase {
	
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


	/**
	 * Return the number of elements in the table managed by the CRUD controller under test
	 * @return int
	 */
	protected function eltCount() {
		return Role::count();
	}
	
	public function test_index_page() {
		Log::Debug(__METHOD__);
		
		$look_for = [__('role.title'), __('role.add'), __('navbar.tenant'), tenant('id')];
		$look_for[] = __('role.name');
		$look_for[] = __('role.description');
		
		$response = $this->get_tenant_url($this->user, 'role', $look_for);		
	}
	
	public function test_create_page() {
		$this->get_tenant_url($this->user, 'role/create', [__('role.new')]);
	}
	
	public function test_store() {
		// Post a creation request
		$role = Role::factory()->make(['name' => 'developer', 'description' => 'Developer']);
		$name = $role->name;
		$description = $role->description;
		$elt = ["name" => $name, "description" => $description, '_token' => csrf_token()];
		
		$initial_count = Role::count ();
		
		// call the post method to create it
		$this->post_tenant_url($this->user, 'role', ['created'], $elt);
		
		$new_count = Role::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "role created, actual=$new_count, expected=$expected" );		
	}
			
	public function test_store_incorrect_value() {
		// Post a creation request
		$bad_name = "Too long............................................................................................................................................................................................................................................................................................................................................................................................................................................";
		$role = Role::factory()->make(['name' => $bad_name, 'description' => 'too long name']);
		$description = $role->description;
		
		$initial_count = Role::count ();
				
		// $url = 'http://' . tenant('id'). '.tenants.com/role' ;
		$elt = ["name" => $bad_name, "description" => $description, '_token' => csrf_token()];

		// 'The name format is invalid'
		$this->post_tenant_url( $this->user, 'role', [], $elt, $errors_expected = true);
		
		$new_count = Role::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "role not created, actual=$new_count, expected=$expected" );
	}
	
	public function ttest_show_page() {
		
		// show page not implemented yet
		
		$role = Role::factory()->make(['name' => "scrum_maser", 'description' => 'Scrum Master']);
		$name = $role->name;
		$role->save();
		
		$this->get_tenant_url($this->user, 'role/' . $name);
	}

	public function test_edit_page() {
		
		$role = Role::factory()->make(['name' => "scrum_maser", 'description' => 'Scrum Master']);
		$id = $role->save();
		
		$this->get_tenant_url($this->user, 'role/' . $id . '/edit', ['Edit role']);
	}

	public function test_update() {
		
		$role = Role::factory()->make(['name' => 'product_owner', 'description' => 'Product Owner']);
		$name = $role->name;
		$description = $role->description;
		$new_description = "new description";
		$elt = ["name" => $name, "description" => $new_description, '_token' => csrf_token()];
		
		$this->assertNotEquals($description, $new_description);
		$id = $role->save();
				
		$this->put_tenant_url($this->user, 'role/' . $id, ['updated'], $elt);
		
		$back = Role::where('name', $name)->first();		
		$this->assertEquals($new_description, $back->description);
		$back->delete();
	}
	
	public function test_delete() {
		
		$role = Role::factory()->make(['name' => 'app.timezone', 'description' => 'Europe/London']);
		$id = $role->save();
		
		$initial_count = Role::count ();
		
		$this->delete_tenant_url($this->user, 'role/' . $id, ['deleted']);
		
		$new_count = Role::count ();
		$expected = $initial_count - 1;
		$this->assertEquals ( $expected, $new_count, "role deleted, actual=$new_count, expected=$expected" );		
	}
	
}
