<?php

/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace Tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\Role;
use Illuminate\Support\Facades\Log;

/**
 * Functional test for the Role CRUD 
 * 
 * It is a functional test which tests
 *   - the routes
 *   - the rolesController class
 *   - the views
 *   - and the model
 *   
 * It is a test at the HTTP level, which means that if the page relies on javascript
 * element to be functional it will not work. Javascript libraries should
 * be used to make the user experience easier, it should not be used to make
 * the application works. Note that this assertion could be reconsidered because of the datatable
 * library
 * 
 * From Laravel documentation each test should only test one request to the controller or the behavior is impredictable.
 * 
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
| Domain | Method    | URI                           | Name              | Action                                                  | Middleware   |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
|        | GET|HEAD  | roles                     | roles.index   | App\Http\Controllers\rolesController@index          | web          |
|        | POST      | roles                     | roles.store   | App\Http\Controllers\rolesController@store          | web          |
|        | GET|HEAD  | roles/create              | roles.create  | App\Http\Controllers\rolesController@create         | web          |
|        | PUT|PATCH | roles/{task}              | roles.update  | App\Http\Controllers\rolesController@update         | web          |
|        | GET|HEAD  | roles/{task}              | roles.show    | App\Http\Controllers\rolesController@show           | web          |
|        | DELETE    | roles/{task}              | roles.destroy | App\Http\Controllers\rolesController@destroy        | web          |
|        | GET|HEAD  | roles/{task}/edit         | roles.edit    | App\Http\Controllers\rolesController@edit           | web          |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
 * 
 * @author frede
 *
 */
class RoleControllerTest extends TenantTestCase {
	
	protected $tenancy = true;
	
	protected $basename = "roles";	
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		
		$this->user = User::factory()->make();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete ();
	}


	/**
	 * 
	 * @param string $segments
	 * @return string
	 */
	protected function base_url($segments = "") {
		$url = "/" . $this->basename;
		if ($segments) {
			$url = join("/", [$url, $segments]);
		}
		return $url;
	}
	
	/**
	 * Return the number of elements in the table managed by the CRUD controller under test
	 * @return int
	 */
	protected function eltCount() {
		return Role::count();
	}
	
    /**
     * Test display of all elements
     * Given the user is logged on
     * When calling index URL
     * Then the table view is displayed
     * 
     * @return void
     */
    public function testBaseUrlDisplaysTableView() {   
		Log::Debug(__METHOD__);
		
		$look_for = [__('role.title'), __('role.add'), __('navbar.tenant'), tenant('id')];
		$look_for[] = __('role.name');
		$look_for[] = __('role.description');
		
		$this->get_tenant_url($this->user, 'role', $look_for);		
	}
	
    /**
     * Test creation form display
     * Given the user is logged on
     * When calling create URL
     * Then the create form is displayed
     */
    public function testCreateUrlDisplaysCreationForm() {
        Log::Debug(__METHOD__);
		$this->get_tenant_url($this->user, 'role/create', [__('role.new')]);
	}
	
    /**
     * Test a post request
     * Given the user is logged on
     * When sending a post request
     * Then the element is stored in the database
     * 
     * @return void
     */
    public function testPostRequestStoresElement()
    {    	        
		Log::Debug(__METHOD__);
		
		// Post a creation request
		$role = Role::factory()->make();
		$elt = ['_token' => csrf_token()];
		$elt['name'] = $role->name;
		$elt['description'] = $role->description;
		
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

	/**
	 * Check that the edit form is correctly displayed
	 * Given the user is logged on
	 * When sending a get request to edit
	 * Then the modification form is displayed pre-filled with the element
	 *
	 * @return void
	 */
	public function testEditUrlDisplaysPopulatedEditForm() {
		
		Log::Debug(__METHOD__);
		
		$role = Role::factory()->make();
		$id = $role->save();
		
		$this->get_tenant_url($this->user, 'role/' . $id . '/edit', ['Edit role']);
	}

	/**
	 * Test an element update
	 * Given the user is logged on
	 * Given at least one element in the table
	 * When sending a put request
	 * Then the element is modified in the database
	 *
	 * @return void
	 */
	public function testPostRequestUpdatesElement() {
		
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
	
	/**
	 * Test delete element
	 * Given the user is logged on
	 * Given at least one element in the table
	 * When sending a delete request
	 * Then the element is removed from the database
	 * @return void
	 */
	public function testDeleteRequestsRemovesElement() {
        Log::Debug(__METHOD__);
		
		$initial_count = Role::count ();
		
		$role = Role::factory()->make();
		$id = $role->save();
		
		$new_count = Role::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "one role created, actual=$new_count, expected=$expected" );
		
		$this->delete_tenant_url($this->user, 'role/' . $id, ['deleted']);
		
		$count_after_delete = Role::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $count_after_delete, "role deleted, actual=$count_after_delete, expected=$expected" );		
	}
	
}
